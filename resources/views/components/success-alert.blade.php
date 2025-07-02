<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
    {{ $slot ?? 'Salvo' }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 2000);
</script>