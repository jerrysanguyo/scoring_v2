<div x-cloak :class="pageLoading ? 'd-flex' : 'd-none'" class="position-fixed w-100 h-100"
    style="inset:0; z-index:1060; background:rgba(0,0,0,.9); align-items:center; justify-content:center;">
    <img src="{{ asset('images/dog.gif') }}" alt="Loading…" class="mb-3"
        style="width: 12rem; height: 12rem; object-fit: contain;" onerror="this.style.display='none';">
    <p class="h5 text-white mb-0">Loading… please wait</p>
</div>