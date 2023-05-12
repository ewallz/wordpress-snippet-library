add_filter( 'woocommerce_email_headers', 'change_reply_to_email_address', 10, 4 );

function change_reply_to_email_address( $header, $email_id, $order, $email ) {
    
      // HERE below set the name and the email address
      $reply_to_name  = 'Put your reply to name';
      $reply_to_email = 'example@example.com';
    
      $header  = "Content-Type: " . $email->get_content_type() . "\r\n";
      $header .= 'Reply-to: ' . $reply_to_name . ' <' . $reply_to_email . ">\r\n";
    
      return $header;
}
