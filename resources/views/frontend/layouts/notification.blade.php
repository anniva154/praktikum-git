@if(session('success') || session('error'))
    <div id="notification-container">
        <div id="notification-box" class="{{ session('success') ? 'alert-success' : 'alert-danger' }}">
            <div class="icon">
                @if(session('success'))
                    <span class="icon">&#10004;</span> <!-- Centang -->
                @elseif(session('error'))
                    <span class="icon">&#10060;</span> <!-- Silang -->
                @endif
            </div>
            <div class="message">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    </div>
@endif

<style>
    #notification-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        width: 500px;
        max-width: 90%;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeOut 0.5s 5s forwards;
    }

    #notification-box {
        position: relative;
        padding: 40px;
        border-radius: 12px;
        font-size: 20px;
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .alert-success {
        border: 2px solid #28a745;
        background-color: #e9f7ef;
        color: #28a745;
    }

    .alert-danger {
        border: 2px solid #dc3545;
        background-color: #f8d7da;
        color: #dc3545;
    }

    .icon {
    font-size: 50px; /* Ukuran ikon */
    color: inherit;
    margin-bottom: 10px;
    transform: none; /* Pastikan tidak ada rotasi */
}


    .message {
        font-size: 20px;
        font-weight: bold;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
            visibility: hidden;
        }
    }
</style>
