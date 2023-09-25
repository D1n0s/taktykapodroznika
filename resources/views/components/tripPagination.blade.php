<h1>Moje podróże</h1>

<!-- Wyświetlanie danych podróży -->
<table class="table">
    <thead>
    <tr>
        <th>Tytuł</th>
        <th>Opis</th>
    </tr>
    </thead>
    <tbody id="trip-table-body">
    @foreach ($trips as $trip)
        <tr>
            <td>{{ $trip->title }}</td>
            <td>{{ $trip->desc }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $trips->links() }}
