<div x-cloak :class="pageLoading ? 'd-flex' : 'd-none'" class="position-fixed w-100 h-100"
    style="inset:0; z-index:1060; background:rgba(0,0,0,.9); align-items:center; justify-content:center; flex-direction:column;">
    
    <img src="{{ asset('images/city_logo.webp') }}" alt="Loading…" class="rotate-logo mb-3"
        style="width: 12rem; height: 12rem; object-fit: contain;" onerror="this.style.display='none';">
        
    <p class="h5 text-white mb-0">Loading… please wait</p>
</div>

<style>
@keyframes rotate360 {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.rotate-logo {
    animation: rotate360 2.5s linear infinite;
    transform-origin: center;
}
</style>