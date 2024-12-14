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