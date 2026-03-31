@extends('paper-pi::layout')

@section('content')
<div class="docs-container">
    <aside class="docs-sidebar">
        @include('paper-pi::partials.navigation')
    </aside>
    
    <main class="docs-content">
        <article>
            {!! $content !!}
        </article>
        
        @if($edit)
            <a href="{{ $edit }}" class="edit-link" target="_blank" rel="noopener">
                Edit this page on GitHub
            </a>
        @endif
    </main>
</div>
@endsection

