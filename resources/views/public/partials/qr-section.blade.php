@if(!empty($guestForQr) && $guestForQr !== null)
<style>
#qr-fab{position:fixed;bottom:24px;right:20px;z-index:9000;display:flex;align-items:center;gap:8px;padding:12px 18px 12px 14px;background:linear-gradient(135deg,#1a0a2e,#2d1b4e);border:1px solid rgba(212,175,55,.45);border-radius:100px;box-shadow:0 8px 28px rgba(0,0,0,.35),0 0 0 1px rgba(212,175,55,.15);cursor:pointer;color:#fff;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;font-weight:600;letter-spacing:.3px;transition:transform .2s,box-shadow .2s;animation:fabPop .5s cubic-bezier(.34,1.56,.64,1) 1.2s both}
#qr-fab:hover{transform:translateY(-2px);box-shadow:0 12px 36px rgba(0,0,0,.4),0 0 0 1px rgba(212,175,55,.25)}
#qr-fab:active{transform:translateY(0)}
#qr-fab svg{width:20px;height:20px;flex-shrink:0}
#qr-fab .fab-label{color:#d4af37}
@keyframes fabPop{from{opacity:0;transform:scale(.7) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)}}
#qr-modal-backdrop{display:none;position:fixed;inset:0;z-index:9500;background:rgba(0,0,0,0);backdrop-filter:blur(0px);-webkit-backdrop-filter:blur(0px);transition:background .35s ease,backdrop-filter .35s ease;align-items:flex-end;justify-content:center}
#qr-modal-backdrop.open{display:flex;background:rgba(0,0,.65);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)}
#qr-modal{width:min(420px,100vw);background:linear-gradient(160deg,#1e0f38 0%,#150a28 100%);border:1px solid rgba(212,175,55,.25);border-radius:28px 28px 0 0;padding:0 0 env(safe-area-inset-bottom,20px);box-shadow:0 -20px 60px rgba(0,0,0,.5);transform:translateY(100%);transition:transform .4s cubic-bezier(.34,1.2,.64,1);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow:hidden}
#qr-modal-backdrop.open #qr-modal{transform:translateY(0)}
.qr-handle{width:40px;height:4px;background:rgba(255,255,255,.2);border-radius:2px;margin:14px auto 0}
.qr-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px 12px}
.qr-header-title{font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(212,175,55,.8)}
.qr-close{width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;cursor:pointer;color:rgba(255,255,255,.7);transition:background .15s}
.qr-close:hover{background:rgba(255,255,255,.15)}
.qr-close svg{width:16px;height:16px}
.qr-body{padding:0 24px 24px;text-align:center}
.qr-guest-name{font-size:clamp(20px,5vw,26px);font-weight:700;color:#fff;margin-bottom:4px;line-height:1.2}
.qr-guest-cat{display:inline-block;padding:3px 12px;border-radius:100px;background:rgba(212,175,55,.12);border:1px solid rgba(212,175,55,.25);font-size:11px;font-weight:600;color:#f0d060;margin-bottom:20px}
.qr-box{display:inline-block;padding:16px;background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,.4);margin-bottom:16px}
.qr-box canvas,.qr-box img{display:block}
.qr-status{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;font-size:12px;font-weight:600;margin-bottom:20px}
.qr-status.checked-in{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#4ade80}
.qr-status.not-checked-in{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6)}
.qr-status svg{width:13px;height:13px}
.qr-hint{font-size:12px;color:rgba(255,255,255,.4);line-height:1.5;padding:0 8px}
.qr-divider{display:flex;align-items:center;gap:12px;margin:16px 0}
.qr-divider-line{flex:1;height:1px;background:rgba(212,175,55,.2)}
.qr-divider-icon{color:rgba(212,175,55,.5);font-size:14px}
.qr-couple{font-size:14px;font-weight:600;color:rgba(255,255,255,.7)}
.qr-couple .amp{color:#d4af37;font-style:italic;margin:0 5px}
</style>

<button id="qr-fab" onclick="openQrModal()" aria-label="Lihat QR Code saya">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="3" height="3" rx=".5"/>
        <rect x="18" y="14" width="3" height="3" rx=".5"/><rect x="14" y="18" width="3" height="3" rx=".5"/>
        <rect x="18" y="18" width="3" height="3" rx=".5"/>
    </svg>
    <span class="fab-label">QR Saya</span>
</button>

<div id="qr-modal-backdrop" onclick="handleBackdropClick(event)">
    <div id="qr-modal" role="dialog" aria-modal="true" aria-label="QR Code Check-in">
        <div class="qr-handle"></div>
        <div class="qr-header">
            <span class="qr-header-title">QR Code Check-in</span>
            <button class="qr-close" onclick="closeQrModal()" aria-label="Tutup">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="qr-body">
            <p class="qr-guest-name">{{ $guestForQr->name }}</p>
            <span class="qr-guest-cat">
                @php $catLabels=['family'=>'Keluarga','friend'=>'Teman','colleague'=>'Rekan']; @endphp
                {{ $catLabels[$guestForQr->category] ?? 'Tamu' }}
            </span>
            <div class="qr-box"><canvas id="qr-canvas"></canvas></div>
            @if($guestForQr->checked_in_at)
            <div class="qr-status checked-in">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Sudah Check-in · {{ $guestForQr->checked_in_at->format('H:i') }} WIB
            </div>
            @else
            <div class="qr-status not-checked-in">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Belum Check-in
            </div>
            @endif
            <div class="qr-divider">
                <div class="qr-divider-line"></div>
                <span class="qr-divider-icon">💍</span>
                <div class="qr-divider-line"></div>
            </div>
            <p class="qr-couple">{{ $invitation->bride_name }}<span class="amp">&amp;</span>{{ $invitation->groom_name }}</p>
            <p class="qr-hint" style="margin-top:12px">Tunjukkan QR ini kepada panitia saat tiba di acara untuk check-in.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
const QR_TOKEN = @json($guestForQr->qr_token);
document.addEventListener('DOMContentLoaded',function(){
    QRCode.toCanvas(document.getElementById('qr-canvas'),QR_TOKEN,{width:200,margin:1,color:{dark:'#1a0a2e',light:'#ffffff'},errorCorrectionLevel:'M'});
});
function openQrModal(){
    const bd=document.getElementById('qr-modal-backdrop');
    bd.style.display='flex';
    requestAnimationFrame(()=>requestAnimationFrame(()=>bd.classList.add('open')));
    document.body.style.overflow='hidden';
}
function closeQrModal(){
    const bd=document.getElementById('qr-modal-backdrop');
    bd.classList.remove('open');
    document.body.style.overflow='';
    setTimeout(()=>{bd.style.display='none';},400);
}
function handleBackdropClick(e){if(e.target===document.getElementById('qr-modal-backdrop'))closeQrModal();}
(function(){
    const modal=document.getElementById('qr-modal');
    let startY=0,isDragging=false;
    modal.addEventListener('touchstart',e=>{startY=e.touches[0].clientY;isDragging=true;},{passive:true});
    modal.addEventListener('touchmove',e=>{if(!isDragging)return;const dy=e.touches[0].clientY-startY;if(dy>0)modal.style.transform=`translateY(${dy}px)`;},{passive:true});
    modal.addEventListener('touchend',e=>{isDragging=false;const dy=e.changedTouches[0].clientY-startY;if(dy>80){modal.style.transform='';closeQrModal();}else{modal.style.transform='';}});
})();
</script>
@endif
