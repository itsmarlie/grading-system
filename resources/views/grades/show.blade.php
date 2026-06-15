<h1>Grades</h1>


@foreach($grades as $grade)
   <p>{{ $grade->subject }} - {{ $grade->score }}</p>
@endforeach


<h3>Average: {{ $average }}</h3>
