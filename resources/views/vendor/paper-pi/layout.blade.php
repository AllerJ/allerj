<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    
    <title>{{ $title ?? 'Documentation' }} - {{ config('app.name') }}</title>
    
    @stack('styles')
    
    <style>
        /* Basic documentation styling - customize as needed */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .docs-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 40px;
            margin-top: 20px;
        }
        
        .docs-sidebar {
            position: sticky;
            top: 20px;
            height: fit-content;
        }
        
        .docs-content {
            max-width: 800px;
        }
        
        .docs-content h1 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
            color: #1a1a1a;
        }
        
        .docs-content h2 {
            font-size: 2em;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            color: #1a1a1a;
        }
        
        .docs-content h3 {
            font-size: 1.5em;
            margin-top: 1.2em;
            margin-bottom: 0.5em;
            color: #1a1a1a;
        }
        
        .docs-content pre {
            background: #f6f8fa;
            border-radius: 6px;
            padding: 16px;
            overflow-x: auto;
        }
        
        .docs-content code {
            background: #f6f8fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
            font-size: 0.9em;
        }
        
        .docs-content pre code {
            background: none;
            padding: 0;
        }
        
        .docs-content blockquote {
            border-left: 4px solid #dfe2e5;
            padding-left: 16px;
            margin-left: 0;
            color: #6a737d;
        }
        
        .edit-link {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 16px;
            background: #f6f8fa;
            border-radius: 6px;
            text-decoration: none;
            color: #0366d6;
            font-size: 0.9em;
        }
        
        .edit-link:hover {
            background: #e1e4e8;
        }
        
        @media (max-width: 768px) {
            .docs-container {
                grid-template-columns: 1fr;
            }
            
            .docs-sidebar {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="/" style="text-decoration: none; color: inherit;">{{ config('app.name') }}</a></h1>
        <nav>
            @yield('header-nav')
        </nav>
    </header>

    @yield('content')

    <footer>
        @yield('footer')
    </footer>

    @stack('scripts')
</body>
</html>

