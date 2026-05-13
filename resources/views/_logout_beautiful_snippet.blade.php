{{-- Reusable logout button snippet for bootstrap pages (POST /logout). --}}
{{-- Usage: include with $class (optional) --}}
@php
    $class = $class ?? '';
@endphp

<style>
    .rm-logout-btn {
        --rm-logout-a: #0d6efd;
        --rm-logout-b: #6610f2;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .35rem .7rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: .875rem;
        border: 1px solid rgba(0,0,0,.08);
        background: linear-gradient(135deg, rgba(13,110,253,.12), rgba(102,16,242,.12));
        color: #2b2f36;
        transition: transform .12s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
        user-select: none;
        text-decoration: none;
    }

    .rm-logout-btn:hover {
        transform: translateY(-1px);
        border-color: rgba(13,110,253,.35);
        box-shadow: 0 10px 30px rgba(13,110,253,.10);
        background: linear-gradient(135deg, rgba(13,110,253,.18), rgba(102,16,242,.18));
        color: #151a21;
    }

    .rm-logout-btn:active {
        transform: translateY(0);
    }

    .rm-logout-icon {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(13,110,253,.18), rgba(102,16,242,.18));
        border: 1px solid rgba(0,0,0,.06);
    }

    .rm-logout-spinner {
        width: 16px;
        height: 16px;
        border-radius: 999px;
        border: 2px solid rgba(43,47,54,.25);
        border-top-color: rgba(43,47,54,.75);
        animation: rmspin .7s linear infinite;
        display: none;
    }

    .rm-logout-btn.rm-logout-busy {
        pointer-events: none;
        opacity: .95;
    }

    .rm-logout-btn.rm-logout-busy .rm-logout-spinner {
        display: inline-block;
    }

    .rm-logout-btn.rm-logout-busy .rm-logout-text {
        opacity: .85;
    }

    @keyframes rmspin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>

<button type="submit" class="rm-logout-btn {{ trim($class) }}" data-logout-submit>

    <span class="rm-logout-icon">
        <i class="bi bi-box-arrow-right" style="font-size:1rem;"></i>
    </span>
    <span class="rm-logout-text">Log out</span>
    <span class="rm-logout-spinner" aria-hidden="true"></span>
</button>

