<x-layout>
    <section>
        <select name="groups" class="form-select form-select-lg bg-light" aria-label="Groups">
            @foreach($groups as $group)
                <option value="{{$group->id}}">{{$group->name}}</option>
            @endforeach
        </select>
    </section>

    <section class="mt-4">
        <h4 class="text-center">{{ $activeGroup->name }}</h4>

        <ul class="list-group">
            @foreach($activeGroup->teams as $key => $team)
                <li class="list-group-item">{{ $key + 1 .'. '. $team->name }}</li>
            @endforeach
        </ul>
    </section>

    <section class="mt-5">
        <x-scores :games="$games"/>
    </section>
@push('afterScripts')
    <script>
        (function() {
            document.querySelector('select[name="groups"]').addEventListener('change', function(e) {
                console.log({ id: e.target.value, name: e.target.options[e.target.selectedIndex].text });
            });
        })();
    </script>
@endpush
</x-layout>
