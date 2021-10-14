<?php 
$request = Request::capture();
$user = UserData::getById($_SESSION['user_id']);


if(isset($request->parameters["operation"])) {
    switch(strtoupper($request->parameters["operation"])) {
        case "OPEN" : 
            $request->ok(CashDeskData::openUserCashDesk($user->id, isset($request->parameters["amount"]) ? $request->parameters["amount"] : 0));
            break;
        case "CLOSE" : 
            $openCashDesk = CashDeskData::getOpenCashDeskByUserId($user->id);
            if($openCashDesk != null) {
                $openCashDesk->close();
                $request->ok($openCashDesk);
            } else {
                $request->notFound();
            }
            break;
        case "ACTUAL" : 
            $openCashDesk = CashDeskData::getOpenCashDeskByUserId($user->id);
            if($openCashDesk != null) {
                $request->ok($openCashDesk);
            } else {
                $request->notFound();
            }
            break;
    }
} else {
    if(isset($request->parameters["id"])) {
        $request->ok(CashDeskData::getById($request->parameters["id"]));
    } else {
        $request->ok(CashDeskData::getAll());
    }
}
?>