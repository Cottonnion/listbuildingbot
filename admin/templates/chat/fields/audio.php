<form action="save_audio.php" method="post" enctype="multipart/form-data" id="lbb-audio-question-form">
    
  
    
    <a id="startRecording"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16 12V6c0-2.217-1.785-4.021-3.979-4.021a.933.933 0 0 0-.209.025A4.006 4.006 0 0 0 8 6v6c0 2.206 1.794 4 4 4s4-1.794 4-4zm-6 0V6c0-1.103.897-2 2-2a.89.89 0 0 0 .163-.015C13.188 4.06 14 4.935 14 6v6c0 1.103-.897 2-2 2s-2-.897-2-2z"></path><path d="M6 12H4c0 4.072 3.061 7.436 7 7.931V22h2v-2.069c3.939-.495 7-3.858 7-7.931h-2c0 3.309-2.691 6-6 6s-6-2.691-6-6z"></path></svg>Start Recording</a>

    <div class="lbb-recording-contorl-buttons-container" style="display:none;">
        <div class="lbb-cancel-recording-button" id="cancelRecording">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
        </div>
        <div class="lbb-recording-elapsed-time">
            <i class="lbb-red-recording-dot " aria-hidden="true"></i>
            <p class="lbb-elapsed-time" id="recordingTimer">00:00</p>
        </div>
        <div class="lbb-stop-recording-button" id="stopRecording">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M9 9h6v6H9z"></path></svg>
        </div>
    </div>

    <input type="hidden" id="audioData" name="audioData">
    <input type="hidden" id="audioData" name="action" value="lbb_audio_save">
</form>

