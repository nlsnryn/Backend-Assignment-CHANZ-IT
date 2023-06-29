@extends('layouts.app')

@section('title', 'Word To Number Conversion')

@section('content')
<div class="text-white w-full">
  <h1 class="text-4xl mb-5 -mt-32">Convert your words to number</h1>

 {{-- WORDS TO NUMBER FORM --}}

  <form method="POST" action="{{  route('convert.convertWords') }}" class="flex flex-col">
    @csrf
    <label for="words" class="mb-3 text-lg">Enter words:</label>

    {{-- INPUT --}}
    @empty($result->input)
    <input type="text" id="words" name="input" class="max-w-xs py-1.5 px-5 rounded mb-10 text-black" placeholder="Enter words" required>
    @else
    <input type="text" id="words" name="input" class="max-w-xs py-1.5 px-5 rounded mb-10 text-black" placeholder="Enter words" value="{{ $result->input }}">
    @endempty
    <button type="submit" class="w-fit bg-red-800 rounded-md px-6 py-1.5">Convert to number</button>
  </form>

    {{-- OUTPUT --}}

    @empty($result->output)
      <input name="output" class="text-gray-800 bg-gray-300 font-semibold text-lg py-1 px-5 rounded mt-5" type="text" disabled/>
    @else
      <input name="output" class="text-gray-800 bg-gray-300 font-semibold text-lg py-1 px-5 rounded mt-5" type="text" value="{{ $result->output}}" disabled/>
    @endempty

    {{-- PHP TO USD CONVERSION --}}

    @empty($result->converted) 
    @else
      <input name="convertedOutput" class="block w-full text-gray-800 bg-gray-300 font-semibold text-lg py-1 px-5 rounded mt-5" type="text" value="If we convert P{{ number_format($result->output, 2)  }} to USD, the amount is ${{ number_format($result->converted->conversion_result, 2)}}" disabled/>
    @endempty

    <button id="toNumberConversion" class="underline text-sm mt-2 hover:text-red-700 block">Want to convert number to words?</button>
</div>

<script>
  $(document).ready(function() {
        $('#toNumberConversion').click(function() {
            window.location.href = "{{ route('convert.number') }}";
        });
  });
</script>
@endsection