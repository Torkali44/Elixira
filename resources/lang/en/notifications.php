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
    'vendor_subscription_confirmed' => [
        'title' => 'Subscription Payment Confirmed',
        'message' => 'Your vendor subscription payment has been confirmed by the admin.',
    ],
    'vendor_subscription_expiring' => [
        'title' => 'Subscription Ending Soon',
        'message' => 'Your vendor subscription expires in :days day(s). Renew now to keep your products visible in the shop.',
    ],
    'vendor_subscription_grace' => [
        'title' => 'Subscription Grace Period',
        'message' => 'Your vendor subscription has ended. You have :days day(s) left to renew before your products are hidden from the shop.',
    ],
    'vendor_subscription_expired' => [
        'title' => 'Subscription Expired',
        'message' => 'Your vendor subscription has fully expired. Renew to restore public product listings.',
    ],
    'contact_message_received' => [
        'title' => 'New Contact Message',
        'message' => ':name sent a message: :subject',
    ],
    'dxn_team_request_received' => [
        'title' => 'New DXN Team Request',
        'message' => ':name submitted a DXN team building request.',
    ],
    'dxn_team_request_updated' => [
        'title' => 'DXN Application :status',
        'message' => 'Your DXN application for ":team_name" is now :status.',
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
