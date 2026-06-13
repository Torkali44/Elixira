<?php

return [
    'order_placed' => [
        'title' => 'Order Placed Successfully',
        'message' => 'Your order #:order has been placed successfully and is pending confirmation.',
    ],
    'new_order_vendor' => [
        'title' => 'New Order Received',
        'message' => 'You have a new order #:order containing your products.',
    ],
    'order_status_updated' => [
        'title' => 'Order Status Updated',
        'message' => 'Your order #:order status has been updated to ":status".',
    ],
    'product_approved' => [
        'title' => 'Product Approved',
        'message' => 'Your product ":product" has been approved by the admin and is now live.',
    ],
    'product_rejected' => [
        'title' => 'Product Rejected',
        'message' => 'Your product ":product" has been rejected.:reason',
    ],
    'vendor_request_updated' => [
        'title' => 'Vendor Request :status',
        'message' => 'Your vendor profile request status is now :status.:reason',
    ],
    'special_request_offer' => [
        'title' => 'Special Request Offer Assigned',
        'message' => 'An offer of quantity :quantity has been assigned to your special request for ":product". You can now purchase it!',
    ],
    'new_review' => [
        'title' => 'New Review/Comment',
        'message' => 'A new review has been submitted by :name and is pending approval.',
    ],
    'rejection_reason' => ' Reason: :reason',
    'vendor_reason' => ' Reason: :reason',
    'status' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'preparing' => 'Preparing',
        'ready' => 'Ready',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'rejected_with_notes' => 'Rejected with Notes',
    ],
];
