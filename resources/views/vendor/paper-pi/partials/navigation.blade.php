<nav class="docs-navigation">
    @php
        $navigation = config("paper-pi.navigation.{$locale}", []);
    @endphp
    
    @foreach($navigation as $section => $links)
        <div class="nav-section">
            <h4>{{ $section }}</h4>
            <ul>
                @foreach($links as $title => $url)
                    <li>
                        <a href="{{ $url }}" 
                           class="{{ request()->is(trim($url, '/')) ? 'active' : '' }}">
                            {{ $title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>

<style>
    .docs-navigation {
        font-size: 0.95em;
    }
    
    .nav-section {
        margin-bottom: 30px;
    }
    
    .nav-section h4 {
        font-size: 0.75em;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6a737d;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .nav-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .nav-section li {
        margin-bottom: 5px;
    }
    
    .nav-section a {
        text-decoration: none;
        color: #0366d6;
        display: block;
        padding: 4px 0;
    }
    
    .nav-section a:hover {
        text-decoration: underline;
    }
    
    .nav-section a.active {
        font-weight: 600;
        color: #1a1a1a;
    }
</style>

