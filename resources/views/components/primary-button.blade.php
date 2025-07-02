<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-secondary text-uppercase btn-sm']) }}>
    {{ $slot }}
</button>
