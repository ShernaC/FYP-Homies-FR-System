<?php 

include_once '../model/subscription.php';

class viewSubscriptionController{
    public function viewSubscription($id){
        $subscription = new Subscription();
        $result = $subscription->viewSubscription($id);
        return $result;
    }
}

class viewSubscriptionDetailsController{
    public function viewSubscriptionDetails($ownerId, $subscriptionId){
        $viewSubscription = new subscriptionDetails();
        $result = $viewSubscription->getSubscriptionDetails($ownerId, $subscriptionId);
        return $result;
    }
}

    
?>