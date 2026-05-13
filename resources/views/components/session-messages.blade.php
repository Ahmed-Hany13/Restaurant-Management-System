    @if (session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="max-width:520px; margin-left:0;">

                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="max-width:520px; margin-left:0;">

                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif



