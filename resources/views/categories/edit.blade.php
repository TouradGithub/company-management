@extends('layouts.mastercomany')

@section('content')


    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="section-header">
      
        <h2>تعديل الفئة </h2>
    </div>

    <div class="add-advance-content">
    <form action="{{ route('categories.update', $category->id) }}" method="POST" id="add-advance-form" class="standard-form">
        @csrf
        @method('PUT')

        <label>الاسم:</label>
        <input type="text" name="name"  class="form-control" value="{{ $category->name }}" required>

        <label>الكود:</label>
        <input type="text" name="code"  class="form-control" value="{{ $category->code }}" required>

        <button  style="margin-top: 10px" class="save-btn" type="submit">تحديث</button>
    </form>
</div>
@endsection
