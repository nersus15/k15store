<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{$pageTitle}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
        @if(isset($css))
           @foreach ($css as $item)
                @if ($item['pos'] == 'head')
                    <link rel="stylesheet" href="{!! asset($item['css'], true) !!}">
                @endif               
           @endforeach
        @endif

        @if (isset($js))
            @foreach ($js as $item)
                @if ($item['pos'] == 'head')
                    <script src="{!! asset($item['js'], true) !!}"></script>
                @endif  
                
            @endforeach
        @endif

        {{-- Javascript inline --}}
        <script class="rahasia">
            var storage_path = "<?php echo $storage_path ?>";
            var path = window.location.origin;
            var resources_path = "<?php {{!!asset($resources_path, true) !!}} ?>";
            var session = <?php echo !empty($user) ? json_encode($user) : "null" ?>;
        </script>

</head>
<body id="app-container" class="menu-default show-spinner">
    {{-- Navbar --}}
    @if(isset($navbar))
        @include($navbar)
    @endif

    {{-- Sidebar --}}
    @if(isset($sidebar))
        @include($sidebar)
    @endif

    {{-- Main content --}}
    @if (isset($content))
        @foreach ($content as $item)
            @include($item)
        @endforeach
    @endif

    {{-- Javascript --}}
    @if (isset($js))
        @foreach ($js as $item)
            @if ($item['pos'] == 'body:end')
                <script src="{!! asset($item['js'], true) !!}"></script>
            @endif  
            
        @endforeach
    @endif    
    
    {{-- Modal Hasi Generate --}}
    <div class="generated-modals"></div>
    @if(isset($footer))
        @include($footer)
    @endif

    <div class="c-overlay">
        <div class="c-overlay-text">Loading</div>
    </div>
</body>
</html>

