<?php
?><div class="tabs">
  <ul id="tabs-nav">
    <li><a href="#tab1">Create Chatbot</a></li>
    <li><a href="#tab2">Settings</a></li>
  </ul> <!-- END tabs-nav -->
  <div id="tabs-content">
    <div id="tab1" class="tab-content">
      <h2>Add Title:</h2>
      <form method="post" id="listbuildingbot-form">
        <input type="hidden" name="action" value="save_action_data">
        <input type="text" name="post_title" id="post_title">
        <span class="title-error" style="display: none;">Please enter title</span>
        <div class="chartflow-select-options">
            <div class="chatflow-custom">
                <label>Knowledge Base</label>
                <input type="radio" name="chatflow_options" value="knowledge_base" checked="">
            </div>
            <div class="chatflow-custom">
                 <label>CHATBOT</label>
                <input type="radio" name="chatflow_options" value="chatbot">
            </div>
        </div>
        <a href="javascript:void(0)" class="next-tab">Next</a>
    </div>
    <div id="tab2" class="tab-content">
      <div class="style-section">
            <h2>Target</h2>
            <label>Website URL</label>
            <select name="target_options" class="target_options">
                <option value="website-url">Website URL</option>
                <option value="query-parameter">Query Parameter</option>
            </select>

            <label>Options</label>
            <select name="pages_options" class="pages_options">
                <option value="is-all-pages">is all pages </option>
                <option value="is">is</option>
                <option value="contains">contains</option>
                <option value="begin-with">beginwith</option>
                <option value="matches">matches</option>
            </select>
        </div>
        <div class="">
            <h2>Decide when the chatflow should appear to visitors. The selected trigger will apply on desktop, tablet, and mobile</h2>

            <div class="lbb-content">
                <label for="exit-intent" class="radio-btn--outer">
                    <input type="radio" id="exit-intent" name="selected_trigger" value="exit_intent" checked="checked">On Exit Intent
                </label>
                
                <label for="time-on-page" class="radio-btn--outer">
                    <input type="radio" id="time-on-page" name="selected_trigger" value="time_page">Time on page in seconds
                </label>

                <label for="time-on-page" class="radio-btn--outer">
                    <input type="radio" id="time-on-page" name="selected_trigger" value="page_scrolled">Percentage of the page scrolled
                
                </label>

            </div>
        </div>
        <input type="submit" value="Next">
    </form>
    </div>
  </div> <!-- END tabs-content -->
</div> <!-- END tabs -->
<input type="hidden" id="site_url" name="site_url" value="<?php echo home_url(); ?>">