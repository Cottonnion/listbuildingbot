<?php
$prompts = array(
    'generate_auto_prompt' => "I'm looking to create a bot that helps my users with a specific topic.

    The topic is:
    {{topic}}
    
    I don't want the bot to respond to anything else.
    
    Can you give me the right prompt for this?

    Add new line on paragraphs
    
    Also, in your response, ONLY give back the final prompt like this:
    
    Say the topic is \"yoga related content\":
    
    This should be response:
    
    Prompt: 
    You are a knowledgeable yoga bot named Yogi. Your job is to help users with various aspects of yoga practice, including poses, breathing techniques, and relaxation exercises. 
    
    Start by asking the user what specific aspect of yoga they need help with. Based on their response, provide step-by-step instructions on how to perform specific poses or techniques. You can also offer modifications or variations to accommodate different skill levels or physical limitations.
    
    Remember to provide clear and concise instructions, using simple language that users can easily understand. Feel free to use visual aids, such as diagrams or images, to help illustrate the instructions.
    
    If the user asks for recommendations on yoga classes or resources, you can suggest local studios, online platforms, or books and videos that they can explore.
    ",
    'lbb_bot' => '%%GOAL%% \n\nMy target audience is:\n%%TARGET_AUDIENCE%% \n\nI want to segment based on responses.\n\nThe goal of the bot is to generate high-quality leads for our business.\n\nNo need to add questions related to contact details.  \n\nLanguage is %%LANG%%\n',

    'lbb_bot_outcome' => '%%GOAL%% \n\nMy target audience is: \n%%TARGET_AUDIENCE%%  \n\nI want to create different outcomes and connect users to the right outcome based on their responses. \n\nNo need to add questions related to contact details. \n\nThese are the outcome titles:\n%%outcome_titles%%  \n\nI want to assign outcome titles based on answers.\n\nLanguages is %%LANG%%',

    'lbb_bot_json' => 'Important: Follow these Rules \n1. Follow this EXACT JSON format. \n2. Map the answers to the right next question. \n3. Total number of questions: Not more than a total of 6 questions in the funnel \n4. In each question, acknowledge the previous response. For e.g., Great! Thank you for the response. \n5. Don\'t need any description. \n6. \'next_question\' must be a number. \n7. Give me the response in JSON format. \n8. NO open-ended questions. Only single choice questions. \n9. Include the next question id in the answer array. \n10. Don\'t give back EMPTY questions. Each question needs to have a title. \n\njson{
        "welcome_message": {
          "message": ""
        },
        "questions": [
          {
            "id": 1,
            "question": "",
            "type" : "single",
            "answers": [
              {
                "text": "",
                "next_question": ""
              }
            ]
          }
        ]
    }',
    'lbb_bot_json_outcome' => 'Important: Follow these Rules \n1. Follow this EXACT JSON format. \n2. Map the answers to the right next question. \n3. Total number of questions: Not more than a total of 6 questions in the funnel \n4. In each question, acknowledge the previous response. For e.g., Great! Thank you for the response. \n5. Don\'t need any description. \n6. \'next_question\' must be a number. \n7. Give me the response in JSON format. \n8. NO open-ended questions. Only single choice questions. \n9. Include the next question id in the answer array. \n 10. Don\'t give back EMPTY questions. Each question needs to have a title.\nAlso connect answer to the right outcome in the format below. \n11. Connect answers to the right outcomes titles. Use the outcome titles mentioned above in the json response (outcome_title field) \njson{
        "welcome_message": {
          "message": ""
        },
        "questions": [
          {
            "id": 1,
            "question": "",
            "type" : "single",
            "answers": [
              {
                "text": "",
                "next_question": "",
                "outcome_title: "outcome 1"
              }
            ]
          }
        ]
    }',
    'lbb_outcome_prompt' => 'I am looking to create a bot funnel where users will be assigned different outcomes based on their answers. \nHere are the bot details:\n\nGOAL: %%GOAL%% \n\nAudience: %%TARGET_AUDIENCE%%\n\nInstructions for response:\nCan you suggest relevant outcome titles and detailed description for each outcome?%%OUTCOME_DESC_LIMIT%%\n\nLanguage is %%LANG%%\n\nGive me the response in JSON format.\n\n[{"outcome_title" : "Outcome title 1","outcome_description" : "Outcome description"}]\n\nIMPORTANT:\nDon\'t add anything else in the JSON response.'
);