<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger text-uppercase btn-sm']) }}>
    {{ $slot }}
</button>
