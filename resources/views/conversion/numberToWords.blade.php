@extends('layouts.app')

@section('title', 'Number To Word Conversion')

@section('content')
<div class="text-white w-full">
  <h1 class="text-4xl mb-5 -mt-32">Convert your number to words</h1>

  {{-- NUMBER TO WORDS FORM --}}

  <form method="POST" action="{{  route('convert.convertNumber') }}" class="flex flex-col">
    @csrf
    <label for="number" class="mb-3 text-lg">Enter your number: </label>

    {{-- INPUT FOR NUMBER TO WORDS --}}
    @empty($result->input)
    <input type="number" id="number" name="input" class="max-w-xs py-1.5 px-2 rounded mb-10 text-black" placeholder="Enter your number"   required>
    @else
    <input type="number" id="number" name="input" class="max-w-xs py-1.5 px-2 rounded mb-10 text-black" placeholder="Enter your number" value="{{ $result->input }}" required>
    @endempty

    <button type="submit" class="w-fit bg-red-800 rounded-md px-6 py-1.5">Convert to words</button>
  </form>

  {{-- OUTPUT IS WORDS --}}

  @empty($result->output)
    <input name="output" class="text-gray-800 bg-gray-300 font-semibold text-lg py-1 px-5 rounded mt-5" type="text" disabled/>
  @else
    <input name="output" class="w-full text-gray-800 bg-gray-300 font-semibold text-lg py-1 px-5 rounded mt-5" type="text" value="{{ $result->output }}" disabled/>
  @endempty
    
  {{-- PHP TO USD CONVERSION --}}

  @empty($result->converted)
  @else
    <input name="convertedOutput" class="block w-full text-gray-800 bg-gray-300 font-semibold text-lg py-1 px-5 rounded mt-5" type="text"value="If we convert P{{ number_format($result->input, 2)  }} to USD, the amount is ${{ number_format($result->converted->conversion_result, 2) }}" disabled/>
  @endempty
    
  <button id="toWordsConversion" class="block underline text-sm mt-2 hover:text-red-700">Want to convert words to number?</button>
</div>

<script>
  $(document).ready(function() {
        $('#toWordsConversion').click(function() {
            window.location.href = "{{ route('convert.words') }}";
        });
  });
</script>
@endsection