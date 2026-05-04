document.addEventListener("DOMContentLoaded", function () {

  // Create a div element to hold the chat widget
  var chatWidgetContainer = document.createElement("div");
  chatWidgetContainer.id = "chat-widget-container"; // Add an ID to the container for styling purposes





  // Load the CSS for the chat widget
  var chatWidgetStylesheet = document.createElement("link");
  chatWidgetStylesheet.rel = "stylesheet";
  chatWidgetStylesheet.href = "https://dapdemo.membershipsitechallenge.com/wp-content/plugins/listbuildingbot/admin/css/chat-fe.css"; // Replace with the actual URL to your CSS file

  // Load the HTML content of the chat widget
  var chatWidgetHTML = ` ADD SHORTCODE HTML`;





  // Append the chat widget container and styles to the host website's DOM
  document.body.appendChild(chatWidgetContainer);
  document.head.appendChild(chatWidgetStylesheet);
  chatWidgetContainer.innerHTML = chatWidgetHTML;


  var customScript = document.createElement("script");

  customScript.innerHTML = `
   var adminImg = '';
    var lbbAdminName = '';
    var userImg = 'https://cdn.chatbot.com/widget/61f28451fdd7c5000728b4f9/FPBAPaZFOOqqiCbV.png';
    var lbbUserName = 'User';
    var chat_mode = 'bot';
    var fieldPlaceholder = {"lbb_input_placeholder_text":"Enter your message","lbb_input_placeholder_email":"Enter your email address","lbb_input_placeholder_name":"Enter your name","lbb_input_placeholder_phone":"Enter your phone","lbb_input_placeholder_country":"Enter your country","lbb_input_placeholder_url":"Enter your URL","lbb_input_placeholder_date":"Enter your date","lbb_conversation_end":"This conversation has ended. Click below to start again.","lbb_guest_user":"Guest User","lbb_required_message":"Please pick or enter a message","lbb_invalid_email":"Please enter valid email address","lbb_invalid_url":"Please enter valid URL address","lbb_invalid_date_format":"Please enter valid date and format should be"};


  var listbuildingbot = {"ajax_url":"https:\/\/dapdemo.membershipsitechallenge.com\/wp-admin\/admin-ajax.php","nonce":"3a2b77a2bd"};

  var firebaseConfig = {"apiKey":"AIzaSyC_iEQzFGRDVxef0cPtWZ4nwX9TCQhXqOc","authDomain":"sqb-smart.firebaseapp.com","databaseURL":"https:\/\/sqb-smart-default-rtdb.firebaseio.com","projectId":"sqb-smart","storageBucket":"sqb-smart.appspot.com","messagingSenderId":"31251435924","appId":"1:31251435924:web:2e076106693f114485bf81"};
  var siteConfig = {"plugin_url":"https:\/\/dapdemo.membershipsitechallenge.com\/wp-content\/plugins\/listbuildingbot\/","ajaxurl":"https:\/\/dapdemo.membershipsitechallenge.com\/wp-admin\/admin-ajax.php","conversationurl":"","welcome_message":"How can I help you?","notify_play_audio":"1","notify_notification":"1","is_wp_admin":"","enable_prompt":"1"};
`;

document.head.appendChild(customScript);


  var chatWidgetScript = document.createElement("script");
  chatWidgetScript.src = "https://dapdemo.membershipsitechallenge.com/wp-content/plugins/listbuildingbot/public/js/listbuildingbot-public.js"; // Replace with the actual URL to your JavaScript file
  document.head.appendChild(chatWidgetScript);

  // Include your chat widget JavaScript file
  var chatWidgetScript1 = document.createElement("script");
  chatWidgetScript1.src = "https://dapdemo.membershipsitechallenge.com/wp-content/plugins/listbuildingbot/public/js/lbb-cookie.min.js"; // Replace with the actual URL to your JavaScript file
  document.head.appendChild(chatWidgetScript1);

  var chatWidgetScript2 = document.createElement("script");
  chatWidgetScript2.src = "https://dapdemo.membershipsitechallenge.com/wp-content/plugins/listbuildingbot/public/js/firebase-custom.js"; // Replace with the actual URL to your JavaScript file
  document.head.appendChild(chatWidgetScript2);

  var chatWidgetScript3 = document.createElement("script");
  chatWidgetScript3.src = "https://dapdemo.membershipsitechallenge.com/wp-content/plugins/listbuildingbot/public/js/firebase-custom.js"; // Replace with the actual URL to your JavaScript file
  document.head.appendChild(chatWidgetScript3);
})();