<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    // GET /api/admin/settings
    public function index(Request $request)
    {
        $group = $request->query('group');

        $query = Setting::orderBy('group')->orderBy('key');
        if ($group) {
            $query->where('group', $group);
        }

        $settings = $query->get()->map(fn($s) => [
            'id'          => $s->id,
            'key'         => $s->key,
            'value'       => $s->type === 'secret' ? $this->maskSecret($s->value) : $s->value,
            'type'        => $s->type,
            'group'       => $s->group,
            'label'       => $s->label,
            'description' => $s->description,
            'is_public'   => $s->is_public,
        ]);

        $groups = Setting::distinct()->pluck('group')->sort()->values();

        return response()->json([
            'success'  => true,
            'settings' => $settings,
            'groups'   => $groups,
        ]);
    }

    // PUT /api/admin/settings/{key}
    public function update(Request $request, string $key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        $validated = $request->validate([
            'value' => 'nullable|string|max:5000',
        ]);

        $newValue = $validated['value'] ?? '';

        // Jika secret dan value kosong/masked, jangan update
        if ($setting->type === 'secret' && (empty($newValue) || str_contains($newValue, '***'))) {
            return response()->json([
                'success' => true,
                'message' => 'Nilai tidak diubah (kosong atau masked)',
                'setting' => $this->formatSetting($setting),
            ]);
        }

        $setting->update(['value' => $newValue]);
        Setting::clearCache();

        // Sync ke config runtime jika Midtrans
        $this->syncMidtransConfig();

        return response()->json([
            'success' => true,
            'message' => "Setting '{$setting->label}' berhasil diperbarui",
            'setting' => $this->formatSetting($setting->fresh()),
        ]);
    }

    // PUT /api/admin/settings (bulk update)
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'settings'       => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable|string|max:5000',
        ]);

        foreach ($validated['settings'] as $item) {
            $setting = Setting::where('key', $item['key'])->first();
            if (!$setting) continue;

            $newValue = $item['value'] ?? '';

            // Skip secret jika masked
            if ($setting->type === 'secret' && str_contains($newValue, '***')) continue;

            $setting->update(['value' => $newValue]);
        }

        Setting::clearCache();
        $this->syncMidtransConfig();

        return response()->json([
            'success' => true,
            'message' => 'Semua setting berhasil diperbarui',
        ]);
    }

    // GET /api/public/settings — hanya setting yang is_public=true
    public function publicSettings()
    {
        $settings = Setting::where('is_public', true)
            ->get()
            ->mapWithKeys(fn($s) => [$s->key => Setting::get($s->key)]);

        return response()->json([
            'success'  => true,
            'settings' => $settings,
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function formatSetting(Setting $s): array
    {
        return [
            'id'          => $s->id,
            'key'         => $s->key,
            'value'       => $s->type === 'secret' ? $this->maskSecret($s->value) : $s->value,
            'type'        => $s->type,
            'group'       => $s->group,
            'label'       => $s->label,
            'description' => $s->description,
            'is_public'   => $s->is_public,
        ];
    }

    private function maskSecret(?string $value): string
    {
        if (empty($value)) return '';
        $len = strlen($value);
        if ($len <= 8) return str_repeat('*', $len);
        return substr($value, 0, 4) . str_repeat('*', $len - 8) . substr($value, -4);
    }

    private function syncMidtransConfig(): void
    {
        // Sync Midtrans config ke runtime agar langsung berlaku tanpa restart
        $serverKey   = Setting::get('midtrans_server_key');
        $clientKey   = Setting::get('midtrans_client_key');
        $merchantId  = Setting::get('midtrans_merchant_id');
        $isProd      = Setting::get('midtrans_is_production', false);
        $price       = Setting::get('invitation_price', 50000);

        if ($serverKey) {
            config([
                'services.midtrans.server_key'    => $serverKey,
                'services.midtrans.client_key'    => $clientKey,
                'services.midtrans.merchant_id'   => $merchantId,
                'services.midtrans.is_production' => $isProd,
                'services.midtrans.price'         => (int) $price,
            ]);
        }
    }
}
