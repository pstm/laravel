@layout('layouts/master')

@section('content')

{{ render('content/'. Muli::get_lang() .'/home') }}

@endsection