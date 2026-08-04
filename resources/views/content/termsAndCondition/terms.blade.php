<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME')}}</title>
</head>
<body>
<h1>{{__('messages.terms_and_conditions.app_terms_and_condition')}}</h1>
<p>{!! $termsAndCondition->message !!}</p>
</body>
</html>
