<div class="text-center mt-3">
    <form action="{{ route(Auth::user()->getRoleNames()->first() . '.logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout
        </button>
    </form>
</div>