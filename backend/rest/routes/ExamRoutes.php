<?php

Flight::route('GET /connection-check', function(){
    $examDao = new ExamDao();
});

Flight::route('GET /customers', function(){
    $examService = new ExamService();
    $customers = $examService->get_customers();
    Flight::json($customers);
});

Flight::route('GET /customer/meals/@customer_id', function($customer_id){
    $examService = new ExamService();
    $meals = $examService->get_customer_meals($customer_id);
    Flight::json($meals);
});

Flight::route('POST /customers/add', function() {
    $examService = new ExamService();
    $data = Flight::request()->data->getData();
    $customer = $examService->add_customer($data);
    Flight::json($customer);

});

Flight::route('GET /foods/report', function(){
    $limit = Flight::request()->query->limit ?? 10;
    $offset = Flight::request()->query->offset ?? 0;
    $foods = Flight::examService()->foods_report($limit, $offset);
    Flight::json($foods);

});

?>
