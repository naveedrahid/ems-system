<footer>
    @php
        $borderBottom = public_path('admin/images/offerborderbottom.PNG');
        $borderBottomData = file_get_contents($borderBottom);
        $borderBottomimg = 'data:image/png;base64,' . base64_encode($borderBottomData);
    @endphp
    {{-- footer start --}}
    <div>
        <img src="{{ $borderBottomimg }}" class="img-fluid">
    </div>
</footer>
{{-- footer end --}}
</body>

</html>
