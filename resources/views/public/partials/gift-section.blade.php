@php
    $isPreview    = !isset($invitation->id) || $invitation->id === 0;
    $invUniqueUrl = $invitation->unique_url ?? 'preview-template';
    $appUrl       = config('app.url');
    $giftEnabled  = $isPreview ? true : ($invitation->gift_enabled ?? false);

    // ── Produk ──
    if ($isPreview) {
        $giftProducts = collect([
            (object)['id'=>1,'name'=>'Dispenser Air Minum','description'=>'Dispenser premium untuk kebutuhan sehari-hari.','image_url'=>null,'price'=>350000,'stock'=>1,'sold'=>1,'remaining'=>0,'buyers'=>collect([(object)['buyer_name'=>'Budi Santoso','buyer_message'=>'Selamat menempuh hidup baru! 🎉','paid_at'=>now()]])],
            (object)['id'=>2,'name'=>'Rice Cooker Digital','description'=>'Rice cooker canggih dengan berbagai fitur memasak.','image_url'=>null,'price'=>550000,'stock'=>2,'sold'=>1,'remaining'=>1,'buyers'=>collect([(object)['buyer_name'=>'Siti Rahayu','buyer_message'=>'Semoga langgeng ya!','paid_at'=>now()]])],
            (object)['id'=>3,'name'=>'Set Peralatan Dapur','description'=>'Lengkapi dapur baru Anda dengan set peralatan berkualitas.','image_url'=>null,'price'=>250000,'stock'=>5,'sold'=>0,'remaining'=>5,'buyers'=>collect()],
            (object)['id'=>4,'name'=>'Blender Portable','description'=>'Blender mini praktis untuk keluarga baru.','image_url'=>null,'price'=>180000,'stock'=>3,'sold'=>0,'remaining'=>3,'buyers'=>collect()],
        ]);
    } else {
        $giftProducts = \App\Models\Product::where('is_active', true)
            ->orderBy('sort_order')->orderBy('name')->get()
            ->map(function ($p) use ($invitation) {
                $soldCount = \App\Models\GiftOrder::where('product_id', $p->id)->where('invitation_id', $invitation->id)->where('status', 'paid')->count();
                $buyers    = \App\Models\GiftOrder::where('product_id', $p->id)->where('invitation_id', $invitation->id)->where('status', 'paid')->orderBy('paid_at')->get(['buyer_name', 'buyer_message', 'paid_at']);
                return (object)['id'=>$p->id,'name'=>$p->name,'description'=>$p->description,'image_url'=>$p->image_path ? asset('storage/'.$p->image_path) : null,'price'=>$p->price,'stock'=>$p->stock,'sold'=>$soldCount,'remaining'=>max(0,$p->stock-$soldCount),'buyers'=>$buyers];
            });
    }

    // ── Rekening bank ──
    if ($isPreview) {
        $bankAccounts = collect([
            (object)['id'=>1,'bank_name'=>'BCA','account_number'=>'1234567890','account_holder'=>'Ahmad Fauzi','owner'=>'groom'],
            (object)['id'=>2,'bank_name'=>'BRI','account_number'=>'0987654321','account_holder'=>'Sarah Amelia','owner'=>'bride'],
        ]);
    } else {
        $bankAccounts = $invitation->bankAccounts ?? collect();
    }

    $hasProducts = $giftProducts->isNotEmpty();
    $hasBanks    = $bankAccounts->isNotEmpty();
    $showSection = $giftEnabled && ($hasProducts || $hasBanks);
@endphp

@if($showSection)
<section id="gift-section" style="padding:3rem 0;background:#fafafa;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow:hidden;">
<style>
#gift-section *{box-sizing:border-box}
.gs-wrap{padding:0 1.25rem}
.gs-heading{margin-bottom:1.5rem}
.gs-heading h2{font-size:clamp(1.1rem,4vw,1.4rem);font-weight:800;color:#1a1a2e;margin:0 0 4px}
.gs-heading p{font-size:.82rem;color:#9ca3af;margin:0}

/* ── Produk horizontal scroll ── */
.gs-track{display:flex;gap:.875rem;overflow-x:auto;padding:0 1.25rem 1rem;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:none}
.gs-track::-webkit-scrollbar{display:none}
.gs-card{flex:0 0 160px;scroll-snap-align:start;background:#fff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:transform .18s,box-shadow .18s;cursor:pointer;position:relative}
.gs-card:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.1)}
.gs-card.gs-soldout{opacity:.6}
.gs-ribbon{position:absolute;top:10px;right:-8px;background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;padding:3px 14px 3px 8px;border-radius:3px 0 0 3px;z-index:1}
.gs-buyer-badge{position:absolute;top:8px;left:8px;background:rgba(107,76,230,.85);backdrop-filter:blur(4px);color:#fff;font-size:.65rem;font-weight:700;padding:3px 7px;border-radius:20px;max-width:110px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;z-index:1}
.gs-img{width:100%;height:120px;object-fit:cover;background:#f3f4f6;display:block}
.gs-img-ph{width:100%;height:120px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;font-size:2.2rem}
.gs-body{padding:.75rem}
.gs-name{font-size:.85rem;font-weight:700;color:#1a1a2e;margin-bottom:.25rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.gs-price{font-size:.95rem;font-weight:800;color:#6b4ce6;margin-bottom:.6rem}
.gs-dots{display:flex;gap:3px;margin-bottom:.6rem;flex-wrap:wrap}
.gs-dot{width:8px;height:8px;border-radius:50%;background:#e5e7eb}
.gs-dot.taken{background:#6b4ce6}
.gs-btn{width:100%;padding:.5rem;border-radius:8px;border:none;font-size:.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;transition:opacity .15s}
.gs-btn-buy{background:linear-gradient(135deg,#6b4ce6,#8b6ff0);color:#fff}
.gs-btn-buy:hover{opacity:.88}
.gs-btn-done{background:#f3f4f6;color:#9ca3af;cursor:not-allowed}

/* ── Rekening bank ── */
.gs-bank-track{display:flex;gap:.875rem;overflow-x:auto;padding:0 1.25rem 1rem;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:none}
.gs-bank-track::-webkit-scrollbar{display:none}
.gs-bank-card{flex:0 0 240px;scroll-snap-align:start;border-radius:16px;padding:1.25rem;position:relative;overflow:hidden;color:#fff;min-height:130px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 4px 16px rgba(0,0,0,.15)}
.gs-bank-card.bride{background:linear-gradient(135deg,#be185d,#ec4899)}
.gs-bank-card.groom{background:linear-gradient(135deg,#1d4ed8,#3b82f6)}
.gs-bank-card.other{background:linear-gradient(135deg,#374151,#6b7280)}
.gs-bank-chip{width:32px;height:24px;background:rgba(255,255,255,.3);border-radius:4px;margin-bottom:.75rem}
.gs-bank-number{font-size:1rem;font-weight:700;letter-spacing:.15em;margin-bottom:.5rem;font-family:monospace}
.gs-bank-bottom{display:flex;justify-content:space-between;align-items:flex-end}
.gs-bank-holder{font-size:.8rem;font-weight:600;opacity:.9;text-transform:uppercase;letter-spacing:.05em}
.gs-bank-name{font-size:.75rem;font-weight:700;opacity:.75;text-align:right}
.gs-bank-copy{position:absolute;top:.75rem;right:.75rem;background:rgba(255,255,255,.2);border:none;border-radius:6px;padding:4px 8px;color:#fff;font-size:.7rem;font-weight:600;cursor:pointer;transition:background .15s}
.gs-bank-copy:hover{background:rgba(255,255,255,.35)}
.gs-bank-copy.copied{background:rgba(34,197,94,.5)}
.gs-bank-owner-badge{position:absolute;bottom:.75rem;right:.75rem;background:rgba(255,255,255,.2);border-radius:20px;padding:2px 8px;font-size:.65rem;font-weight:700;opacity:.85}

/* ── Modal ── */
#gs-modal-bd{display:none;position:fixed;inset:0;z-index:9800;background:rgba(0,0,0,.55);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);align-items:flex-end;justify-content:center}
#gs-modal-bd.open{display:flex}
#gs-modal{width:min(480px,100vw);background:#fff;border-radius:22px 22px 0 0;padding:0 0 env(safe-area-inset-bottom,20px);box-shadow:0 -16px 48px rgba(0,0,0,.18);transform:translateY(100%);transition:transform .32s cubic-bezier(.34,1.2,.64,1);max-height:88vh;overflow-y:auto}
#gs-modal-bd.open #gs-modal{transform:translateY(0)}
.gs-handle{width:36px;height:4px;background:#e5e7eb;border-radius:2px;margin:12px auto 0}
.gs-mhead{padding:14px 18px 10px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f3f4f6}
.gs-mtitle{font-size:.95rem;font-weight:700;color:#1a1a2e}
.gs-mclose{width:30px;height:30px;border-radius:50%;background:#f3f4f6;border:none;cursor:pointer;font-size:1rem;color:#6b7280;display:flex;align-items:center;justify-content:center}
.gs-mbody{padding:18px}
.gs-mprod{display:flex;align-items:center;gap:10px;padding:10px;background:#f9fafb;border-radius:10px;margin-bottom:16px}
.gs-mpimg{width:52px;height:52px;border-radius:9px;object-fit:cover;background:#ede9fe;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
.gs-mpname{font-size:.9rem;font-weight:700;color:#1a1a2e}
.gs-mpprice{font-size:.95rem;font-weight:800;color:#6b4ce6;margin-top:2px}
.gs-fg{margin-bottom:13px}
.gs-fl{display:block;font-size:.72rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px}
.gs-fi{width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.9rem;color:#1a1a2e;background:#fff;transition:border-color .15s;font-family:inherit}
.gs-fi:focus{outline:none;border-color:#6b4ce6}
.gs-fta{resize:vertical;min-height:72px}
.gs-sbtn{width:100%;padding:13px;background:linear-gradient(135deg,#6b4ce6,#8b6ff0);color:#fff;border:none;border-radius:11px;font-size:.95rem;font-weight:700;cursor:pointer;margin-top:6px;transition:opacity .15s}
.gs-sbtn:hover{opacity:.9}
.gs-sbtn:disabled{opacity:.55;cursor:not-allowed}
.gs-err{color:#ef4444;font-size:.78rem;margin-top:5px}
.gs-buyers-list{margin-bottom:14px}
.gs-buyers-title{font-size:.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px}
.gs-buyer-row{display:flex;align-items:flex-start;gap:7px;padding:5px 0;border-top:1px solid #f3f4f6}
.gs-bav{width:26px;height:26px;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#6b4ce6;flex-shrink:0}
.gs-bname{font-size:.78rem;font-weight:600;color:#374151}
.gs-bmsg{font-size:.72rem;color:#9ca3af;font-style:italic;margin-top:1px}
.gs-sub-label{font-size:.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;padding:0 1.25rem;margin-bottom:.6rem;margin-top:1.5rem;display:block}
@media(max-width:400px){.gs-card{flex:0 0 145px}.gs-bank-card{flex:0 0 210px}}
</style>

<div class="gs-wrap">
    <div class="gs-heading">
        <h2>🎁 Kirim Hadiah</h2>
        <p>untuk {{ $bride_name }} & {{ $groom_name }}</p>
    </div>
</div>

@if($hasProducts)
<span class="gs-sub-label">Hadiah Produk</span>
<div class="gs-track">
    @foreach($giftProducts as $product)
    @php $isSoldOut = $product->remaining <= 0; $firstBuyer = $product->buyers->first(); @endphp
    <div class="gs-card {{ $isSoldOut ? 'gs-soldout' : '' }}" onclick="{{ $isSoldOut ? '' : "openGsModal({$product->id},'" . addslashes($product->name) . "',{$product->price},'" . ($product->image_url ?? '') . "')" }}">
        @if($isSoldOut)<div class="gs-ribbon">Dipesan</div>@endif
        @if($firstBuyer && !$isSoldOut)<div class="gs-buyer-badge">✓ {{ $firstBuyer->buyer_name }}</div>@endif
        @if($product->image_url)<img class="gs-img" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        @else<div class="gs-img-ph">🎁</div>@endif
        <div class="gs-body">
            <div class="gs-name">{{ $product->name }}</div>
            <div class="gs-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            @if($product->stock <= 5)
            <div class="gs-dots">@for($d=0;$d<$product->stock;$d++)<div class="gs-dot {{ $d < $product->sold ? 'taken' : '' }}"></div>@endfor</div>
            @endif
            @if($isSoldOut)<button class="gs-btn gs-btn-done" disabled>✓ Sudah Dipesan</button>
            @else<button class="gs-btn gs-btn-buy">🎁 Hadiahkan</button>@endif
        </div>
    </div>
    @endforeach
</div>
@endif

@if($hasBanks)
<span class="gs-sub-label">Transfer Rekening</span>
<div class="gs-bank-track">
    @foreach($bankAccounts as $bank)
    @php
        $ownerLabel = $bank->owner === 'bride' ? $bride_name : ($bank->owner === 'groom' ? $groom_name : 'Bersama');
        $cardClass  = $bank->owner === 'bride' ? 'bride' : ($bank->owner === 'groom' ? 'groom' : 'other');
        $maskedNum  = preg_replace('/(\d{4})(?=\d)/', '$1 ', $bank->account_number);
    @endphp
    <div class="gs-bank-card {{ $cardClass }}">
        <button class="gs-bank-copy" onclick="copyBankNum('{{ $bank->account_number }}', this)">Salin</button>
        <div>
            <div class="gs-bank-chip"></div>
            <div class="gs-bank-number">{{ $maskedNum }}</div>
        </div>
        <div class="gs-bank-bottom">
            <div class="gs-bank-holder">{{ $bank->account_holder }}</div>
            <div class="gs-bank-name">{{ strtoupper($bank->bank_name) }}</div>
        </div>
        <div class="gs-bank-owner-badge">{{ $ownerLabel }}</div>
    </div>
    @endforeach
</div>
@endif

{{-- Modal produk --}}
<div id="gs-modal-bd" onclick="gsBackdropClick(event)">
    <div id="gs-modal">
        <div class="gs-handle"></div>
        <div class="gs-mhead">
            <span class="gs-mtitle">Kirim Hadiah</span>
            <button class="gs-mclose" onclick="closeGsModal()">✕</button>
        </div>
        <div class="gs-mbody" id="gs-mbody"></div>
    </div>
</div>

<script>
(function () {
    const UNIQUE_URL = @json($invUniqueUrl);
    const API_BASE   = @json($appUrl . '/api');
    const IS_PREVIEW = @json($isPreview);
    const BUYERS_DATA = @json($giftProducts->mapWithKeys(fn($p) => [$p->id => $p->buyers->map(fn($b) => ['name' => $b->buyer_name, 'msg' => $b->buyer_message])->values()]));
    let cur = null;

    window.openGsModal = function (id, name, price, img) {
        cur = { id, name, price, img };
        renderGsForm();
        const bd = document.getElementById('gs-modal-bd');
        bd.style.display = 'flex';
        requestAnimationFrame(() => requestAnimationFrame(() => bd.classList.add('open')));
        document.body.style.overflow = 'hidden';
    };
    window.closeGsModal = function () {
        const bd = document.getElementById('gs-modal-bd');
        bd.classList.remove('open');
        document.body.style.overflow = '';
        setTimeout(() => { bd.style.display = 'none'; }, 380);
    };
    window.gsBackdropClick = function (e) {
        if (e.target === document.getElementById('gs-modal-bd')) closeGsModal();
    };
    window.copyBankNum = function (num, btn) {
        navigator.clipboard?.writeText(num).then(() => {
            btn.textContent = '✓ Tersalin';
            btn.classList.add('copied');
            setTimeout(() => { btn.textContent = 'Salin'; btn.classList.remove('copied'); }, 2000);
        }).catch(() => {
            const ta = document.createElement('textarea');
            ta.value = num; document.body.appendChild(ta); ta.select();
            document.execCommand('copy'); document.body.removeChild(ta);
            btn.textContent = '✓ Tersalin'; btn.classList.add('copied');
            setTimeout(() => { btn.textContent = 'Salin'; btn.classList.remove('copied'); }, 2000);
        });
    };

    function fmt(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

    function renderGsForm() {
        const p = cur;
        const buyers = BUYERS_DATA[p.id] || [];
        const buyersHtml = buyers.length ? `<div class="gs-buyers-list"><div class="gs-buyers-title">Sudah dihadiahkan oleh</div>${buyers.map(b => `<div class="gs-buyer-row"><div class="gs-bav">${b.name.charAt(0).toUpperCase()}</div><div><div class="gs-bname">${b.name}</div>${b.msg ? `<div class="gs-bmsg">"${b.msg}"</div>` : ''}</div></div>`).join('')}</div>` : '';
        document.getElementById('gs-mbody').innerHTML = `
            <div class="gs-mprod">${p.img ? `<img class="gs-mpimg" src="${p.img}" alt="${p.name}">` : `<div class="gs-mpimg">🎁</div>`}<div><div class="gs-mpname">${p.name}</div><div class="gs-mpprice">${fmt(p.price)}</div></div></div>
            ${buyersHtml}
            <div class="gs-fg"><label class="gs-fl">Nama Lengkap *</label><input id="gs-name" class="gs-fi" type="text" placeholder="Nama Anda" required></div>
            <div class="gs-fg"><label class="gs-fl">Email *</label><input id="gs-email" class="gs-fi" type="email" placeholder="email@contoh.com" required></div>
            <div class="gs-fg"><label class="gs-fl">No. WhatsApp</label><input id="gs-phone" class="gs-fi" type="tel" placeholder="08xxxxxxxxxx"></div>
            <div class="gs-fg"><label class="gs-fl">Pesan untuk Pengantin</label><textarea id="gs-msg" class="gs-fi gs-fta" placeholder="Tulis ucapan selamat..."></textarea></div>
            <div id="gs-err" class="gs-err" style="display:none"></div>
            ${IS_PREVIEW ? `<button class="gs-sbtn" style="background:#9ca3af;cursor:not-allowed" disabled>Mode Preview — Tidak Bisa Bayar</button>` : `<button class="gs-sbtn" id="gs-sbtn" onclick="submitGsOrder()">Lanjutkan Pembayaran · ${fmt(p.price)}</button>`}
        `;
    }

    window.submitGsOrder = async function () {
        if (IS_PREVIEW) return;
        const name = document.getElementById('gs-name').value.trim();
        const email = document.getElementById('gs-email').value.trim();
        const phone = document.getElementById('gs-phone').value.trim();
        const msg   = document.getElementById('gs-msg').value.trim();
        const err   = document.getElementById('gs-err');
        const btn   = document.getElementById('gs-sbtn');
        err.style.display = 'none';
        if (!name)  { err.textContent = 'Nama harus diisi.'; err.style.display = 'block'; return; }
        if (!email || !email.includes('@')) { err.textContent = 'Email tidak valid.'; err.style.display = 'block'; return; }
        btn.disabled = true; btn.textContent = 'Memproses...';
        try {
            const res  = await fetch(`${API_BASE}/public/invitations/${UNIQUE_URL}/gifts/order`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ product_id: cur.id, buyer_name: name, buyer_email: email, buyer_phone: phone || null, buyer_message: msg || null }) });
            const data = await res.json();
            if (!data.success) { err.textContent = data.message || 'Gagal membuat pesanan.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = `Lanjutkan Pembayaran · ${fmt(cur.price)}`; return; }
            window.location.href = (data.is_production ? 'https://app.midtrans.com/snap/v2/vtweb/' : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/') + data.snap_token;
        } catch (e) {
            err.textContent = 'Terjadi kesalahan. Coba lagi.'; err.style.display = 'block';
            btn.disabled = false; btn.textContent = `Lanjutkan Pembayaran · ${fmt(cur.price)}`;
        }
    };

    (function () {
        const m = document.getElementById('gs-modal');
        let sy = 0, drag = false;
        m.addEventListener('touchstart', e => { sy = e.touches[0].clientY; drag = true; }, { passive: true });
        m.addEventListener('touchmove', e => { if (!drag) return; const dy = e.touches[0].clientY - sy; if (dy > 0) m.style.transform = `translateY(${dy}px)`; }, { passive: true });
        m.addEventListener('touchend', e => { drag = false; const dy = e.changedTouches[0].clientY - sy; if (dy > 80) { m.style.transform = ''; closeGsModal(); } else { m.style.transform = ''; } });
    })();
})();
</script>
</section>
@endif
