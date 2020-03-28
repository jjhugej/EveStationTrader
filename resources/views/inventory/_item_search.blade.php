
<ul id ="searchResultContainer"class="list-group overflow-auto">
    @foreach($searchMatches as $searchMatch)
    <li class="list-group-item"> 
        <button class="btn btn-block text-left item-search-button" type="button" value="{{$searchMatch->typeName}}">
            {{$searchMatch->typeName}}
        </button>
    </li>
    @endforeach
</ul>