<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Plugin-related strings
$string['pluginname'] = 'DigitalTA';

// Settings
$string['config:issuerid'] = 'OAuth service for Google Meet integration';

// General
$string['general:platform_name']       = 'Teacher Academy';
$string['general:required']            = '<span style="color: red;">*</span> Elements marked with a red asterisk are required.';
$string['general:no_elements']         = 'No elements to show';
$string['general:see_more']            = 'See more';
$string['general:learn_more']          = 'Learn more';
$string['general:video_not_supported'] = 'Your browser does not support the video tag.';

// Concepts / terms
$string['concept:experience']  = 'Experience';
$string['concept:experiences'] = 'Experiences';
$string['concept:case']        = 'Case';
$string['concept:cases']       = 'Cases';
$string['concept:resource']    = 'Resource';
$string['concept:resources']   = 'Resources';
$string['concept:user']        = 'User';
$string['concept:users']       = 'Users';
$string['concept:theme']       = 'Theme';
$string['concept:themes']      = 'Themes';
$string['concept:tag']         = 'Tag';
$string['concept:tags']        = 'Tags';
$string['concept:themestags']  = 'Themes & Tags';

// Concepts / terms - Definitions
$string['concept:experience:definition'] = 'An experience is a real-world teaching practice shared by a teacher. It can be a lesson plan, a classroom activity, a reflection, or any other teaching-related content.';
$string['concept:case:definition']       = 'A case is a detailed description of a real-world teaching experience. It includes the context, the problem, the actions taken, the results, and the reflections of the teacher.';
$string['concept:resource:definition']   = 'A resource is a teaching-related material that can be used to support a lesson, an activity, or a reflection. It can be a book, a video, a website, or any other type of content.';
$string['concept:theme:definition']      = 'A theme is a broad topic or subject that is relevant to teaching and education. It can be used to categorize experiences, cases, and resources.';
$string['concept:tag:definition']        = 'A tag is a keyword or label that is used to describe the content of an experience, a case, or a resource. It can be used to facilitate search and discovery.';

// Concepts / terms - Tutorials
$string['concept:experience:tutorial_intro'] = 'Experiences are the heart of the Teacher Academy. They are real-world teaching practices shared by teachers like you. You can explore experiences, reflect on them, and learn from the insights and experiences of your peers.';
$string['concept:experience:tutorial_steps'] = '<ol>
    <li>Add a title to your experience.</li>
    <li>Fill in the required fields (language, picture, visibility, themes).</li>
    <li>Enter a brief description of your experience.</li>
    <li>Click on the "Post" button to publish your experience.</li>
</ol>';

// Visibility
$string['visibility:public']  = 'Public';
$string['visibility:private'] = 'Private';

// Teacher Academy
$string['teacheracademy:header']      = $string['general:platform_name'];
$string['teacheracademy:title']       = $string['general:platform_name'];
$string['teacheracademy:description'] = '<p>Welcome to <span class="dta-highlighted-upper">Teacher Academy</span>, your collaborative space for professional growth! Here, you can explore <span class="dta-highlighted">real-world classroom experiences</span>, connect with <span class="dta-highlighted">mentors</span>, access a wealth of <span class="dta-highlighted">teaching resources</span>, ask <span class="dta-highlighted">questions</span>, and draw inspiration from diverse <span class="dta-highlighted">teaching practices</span>. Embark on your journey by engaging with our vibrant community and empowering the next generation of learners. Let\'s transform education together!</p>';

// Teacher Academy - Actions
$string['teacheracademy:actions:question']    = '{$a}, what do you want to do today?';
$string['teacheracademy:actions:explore']     = 'Explore Experiences';
$string['teacheracademy:actions:ask']         = 'Ask a Question';
$string['teacheracademy:actions:share']       = 'Share your Experience';
$string['teacheracademy:actions:connect']     = 'Connect with Experts';
$string['teacheracademy:actions:discover']    = 'Discover Resources';
$string['teacheracademy:actions:getinspired'] = 'Get Inspired by Real Cases';

// Teacher Academy - Actions - Add modal
$string['teacheracademy:actions:ask:description']   = 'Asking a question about a particular teaching experience is a great way to initiate your reflection process. By doing so, you will be able to get feedback from other teachers and experts, and learn from their experiences.';
$string['teacheracademy:actions:ask:title'] = 'Start by entering your <span class="dta-highlighted">question</span> below:';
$string['teacheracademy:actions:ask:title:placeholder'] = 'Enter your question...';
$string['teacheracademy:actions:ask:picture'] = 'Upload a picture that reflects your question. This image is optional and will serve as decoration only. Additional images can be added to the question description.';
$string['teacheracademy:actions:ask:visibility'] = 'Do you want your question to be <span class="dta-highlighted">public</span> or <span class="dta-highlighted">private</span>?';
$string['teacheracademy:actions:ask:language'] = 'Select the <span class="dta-highlighted">language</span> of your question:';
$string['teacheracademy:actions:ask:themes'] = 'Select the <span class="dta-highlighted">themes</span> that best describe your question:';
$string['teacheracademy:actions:ask:tags'] = 'Add <span class="dta-highlighted">tags</span> to your question:';

$string['teacheracademy:actions:share:description'] = 'Sharing your experience is a powerful way to reflect on your teaching practice, learn from others, and inspire fellow educators.';
$string['teacheracademy:actions:share:title'] = 'Start by entering a meaningful <span class="dta-highlighted">title</span> for your experience:';
$string['teacheracademy:actions:share:title:placeholder'] = 'Enter a title...';
$string['teacheracademy:actions:share:picture'] = 'Upload a picture that reflects your experience. This image is optional and will serve as decoration only. Additional images can be added to the experience description.';
$string['teacheracademy:actions:share:visibility'] = 'Do you want your experience to be <span class="dta-highlighted">public</span> or <span class="dta-highlighted">private</span>?';
$string['teacheracademy:actions:share:language'] = 'Select the <span class="dta-highlighted">language</span> of your experience:';
$string['teacheracademy:actions:share:themes'] = 'Select the <span class="dta-highlighted">themes</span> that best describe your experience:';
$string['teacheracademy:actions:share:tags'] = 'Add <span class="dta-highlighted">tags</span> to your experience:';

$string['teacheracademy:actions:add_experience:description'] = 'Briefly describe the <span class="dta-highlighted">context</span> of your experience. Describe what is happening in your class? What is the issue? What were you thinking/feeling?';

// Themes & Tags
$string['themestags:title']            = $string['concept:themestags'];
$string['themestags:header']           = $string['concept:themestags'];
$string['themestags:description']      = '<p>Explore our <span class="dta-highlighted">themes</span> and <span class="dta-highlighted">tags</span> to find the most relevant <span class="dta-highlighted">experiences</span>, <span class="dta-highlighted">cases</span>, and <span class="dta-highlighted">resources</span> shared by our community.</p>';
$string['themestags:view:description'] = '<p>Discover the most relevant <span class="dta-highlighted">experiences</span>, <span class="dta-highlighted">cases</span>, and <span class="dta-highlighted">resources</span> shared by our community about <span class="dta-highlighted-upper">{$a}</span>.';
$string['invalidthemetag']             = 'Invalid tag or theme';
$string['invalidthemename']            = 'Invalid theme name';
$string['invalidtagname']              = 'Invalid tag name';









// Experience add form
$string['form_experience_header'] = 'Add new experience'; 
$string['form_experience_title'] = 'Title'; 
$string['form_experience_description'] = 'Description';
$string['form_experience_lang'] = 'Language';
$string['form_experience_picture'] = 'Featured image';
$string['form_experience_visibility'] = 'Visibility';
$string['form_experience_visibility_public'] = 'Public';
$string['form_experience_visibility_private'] = 'Private';
$string['form_experience_tags'] = 'Tags';
$string['form_experience_tags_placeholder'] = 'Select or create a tag...';
$string['form_experience_selected_tags'] = 'Selected tags';

// Experience delete form
$string['form_experience_delete_header'] = 'Delete experience';
$string['form_experience_delete_confirm'] = 'Are you sure you want to delete this experience?';
$string['form_experience_delete_yes'] = 'Experience deleted successfully'; 
$string['form_experience_delete_no'] = 'No';

// Experiences view
$string['experiences_header'] = 'Experiences'; 
$string['experiences_title'] = 'Experiences';
$string['experiences_post_button'] = 'Share';
$string['experiences_post_placeholder'] = 'Share your teaching experience with the community';

// Profile
$string['profile_header'] = 'Profile';
$string['profile_title'] = 'Profile';

// Experience manage
$string['experience_add_new_experience'] = 'Add new experience';
$string['experience_add_comment'] = 'Add a new comment...';
$string['experience_picture_alt'] = 'Experience picture';
$string['experience_not_found'] = 'Experience not found';
$string['experience_featured'] = 'Featured';

// Experience view
$string['experience_view_cases_title'] = 'Cases';
$string['experience_view_cases_desc_nocases'] = 'There are not cases available.';
$string['experience_view_mytutoring_title'] = 'My Tutoring';
$string['experience_view_mytutoring_request_title'] = 'Mentors';
$string['experience_view_mytutoring_request_not_found'] = 'No mentors to this experience.';
$string['experience_view_mytutoring_desc_noreq'] = 'Waiting request...';
$string['experience_view_mytutoring_ok_button'] = 'Accept';
$string['experience_view_mytutoring_decline_button'] = 'Decline';
$string['experience_view_reflectionmanager_title'] = 'Reflection';
$string['experience_view_resrepo_title'] = 'Resources';
$string['experience_view_resrepo_desc_nores'] = 'There are not resources available.';
$string['experience_view_resrepo_action_tooltip'] = 'Import resources';
$string['experience_view_block_modal_title'] = 'Block your experience';
$string['experience_view_block_modal_confirm'] = 'Close experience';
$string['experience_view_block_modal_text'] = 'Are you sure you want to close this experience?';

// Experience View Tooltips
$string['experience_view_tooltip_cases'] = 'Link a case to this experience';
$string['experience_view_tooltip_mytutoring'] = 'Manage your tutoring sessions';
$string['experience_view_tooltip_reflectionmanager'] = 'Manage your reflections for this experience';
$string['experience_view_tooltip_resrepo'] = 'Link resources to this experience';

// Reflection
$string['experience_reflection_header'] = 'Reflection Creator';
$string['experience_reflection_view_header'] = 'Reflection View';
$string['experience_reflection_section_what_title'] = 'What?';
$string['experience_reflection_section_what_question_1_title'] = 'Introduction';
$string['experience_reflection_section_what_question_1_desc'] = 'Briefly describe the context of the experience.';
$string['experience_reflection_section_what_question_2_title'] = 'Problem context';
$string['experience_reflection_section_what_question_2_desc'] = 'Describe what is happening in your class? What is the issue? What were you thinking/feeling?';
$string['experience_reflection_section_sowhat_title'] = 'So What?';
$string['experience_reflection_section_sowhat_question_1_title'] = 'How did I find out more...';
$string['experience_reflection_section_sowhat_question_1_desc'] = '...about this: colleagues, literature, mentor, .... What did you read, who did you ask?';
$string['experience_reflection_section_nowwhat_title'] = 'Now What?';
$string['experience_reflection_section_nowwhat_question_1_title'] = 'Action';
$string['experience_reflection_section_nowwhat_question_1_desc'] = 'What did I do, why. What happened when I tried it out.';
$string['experience_reflection_section_nowwhat_question_2_title'] = 'Reflection';
$string['experience_reflection_section_nowwhat_question_2_desc'] = 'What did I learn and gain from this. What impact did it have? What should I do next?';
$string['experience_reflection_section_extra_title'] = 'Extra';
$string['experience_reflection_section_extra_question_1_title'] = 'Extra resources';
$string['experience_reflection_section_extra_question_1_desc'] = 'Add extra resources to this reflection';

// Reflection - Import Case Modal
$string['experience_reflection_import_cases_title'] = 'Import Cases';
$string['experience_reflection_import_cases_searchbar_placeholder'] = 'Search a case...';
$string['experience_reflection_import_cases_button'] = 'Import';
$string['experience_reflection_import_cases_nocases'] = 'No cases found.';

// Cases main
$string['cases_header_title'] = 'Case title';
$string['cases_header_description'] = 'Case description';
$string['cases_header_action_button'] = 'Edit case';
$string['cases_section_text_delete_modal_title'] = 'Delete selected section';
$string['cases_section_text_delete_modal_body'] = 'Are you sure you want to delete this section?';
$string['cases_button_save'] = 'Save case';
$string['cases_modal_save_title'] = 'Save case';
$string['cases_modal_save_body'] = 'Are you ready to save this case?';

// Experience delete form
$string['form_case_delete_header'] = 'Delete case';
$string['form_case_delete_confirm'] = 'Are you sure you want to delete this case?';
$string['form_case_delete_yes'] = 'Case deleted successfully'; 
$string['form_case_delete_no'] = 'No';

// Cases repository
$string['cases_repository_placeholder'] = 'Create a new case';
$string['cases_repository_title'] = 'Study Cases';
$string['cases_header'] = 'Cases'; 
$string['cases_title'] = 'Cases';
$string['cases_add_new_case'] = 'Add new case';

// Reaction Buttons
$string['reaction_buttons_flag_title'] = 'Report';

// General
$string['avatar_alt'] = 'User avatar';
$string['view'] = "View";
$string['edit'] = 'Edit';
$string['delete'] = 'Delete';
$string['create_case'] = 'Create case';
$string['cancel'] = 'Cancel';
$string['save'] = 'Save';
$string['finish'] = 'Finish';
$string['see_more'] = 'See More';
$string['coming_soon'] = 'Coming soon...';

// Date format strings
$string['date_timeago'] = '{$a} ago';
$string['date_justnow'] = 'Just now';

// Repository 
$string['repository_title'] = 'Resource Repository';
$string['repository_header'] = 'Resource Repository';

// Mentor Repository - MyTutoring
$string['mentor_page_title'] = 'Mentors';
$string['mentor_searchbar_placeholder'] = 'Search a mentor...';
$string['mentor_card_action_add_contact'] = 'Request contact';
$string['mentor_card_action_send_email'] = 'Send email';
$string['mentor_card_action_view_profile'] = 'View profile';

// Filters
$string['filter_themes_label'] = 'Themes';
$string['filter_themes_placeholder'] = 'Select a theme...';
$string['filter_tags_label'] = 'Tags';
$string['filter_tags_placeholder'] = 'Select a tag...';
$string['filter_language_label'] = 'Language';
$string['filter_language_placeholder'] = 'Select a language...';

// Tutoring
$string['tutoring:request'] = 'Tutoring Request';
$string['tutoring:title'] = 'MY TUTORING';
$string['tutoring:back_to_chat'] = 'Back to chat';
$string['tutoring:mentor_comments'] = 'Mentor comments';
$string['tutoring:see_all_mentors'] = 'See all mentors';
$string['tutoring:open_chats'] = 'Open chats';
$string['tutoring:view_tooltip'] = 'View tooltip';
$string['tutoring:videocallbutton'] = 'Start Google Meet call';
$string['tutoring:joinvideocall'] = 'Join Google Meet call';
$string['tutoring:closevideocall'] = 'Close call';
$string['tutoring:at_university'] = 'Teacher at';










// Elements - Components
$string['component:experience'] = $string['concept:experience'];
$string['component:case']       = $string['concept:case'];
$string['component:resource']   = $string['concept:resource'];
$string['component:user']       = $string['concept:user'];

// Elements - Modifiers
$string['modifier:theme'] = $string['concept:theme'];
$string['modifier:tag']   = $string['concept:tag'];

// Elements - Themes
$string['theme:digital_technology']                         = 'Digital Technology';
$string['theme:classroom_management']                       = 'Classroom Management';
$string['theme:communication_and_relationship_building']    = 'Communication and Relationship Building';
$string['theme:diversity_and_inclusion']                    = 'Diversity and Inclusion';
$string['theme:professional_collaboration_and_development'] = 'Professional Collaboration and Development';
$string['theme:school_culture']                             = 'School Culture';
$string['theme:curriculum_planning_and_development']        = 'Curriculum Planning and Development';
$string['theme:others']                                     = 'Others';

// Elements - Resource types
$string['resource_type:other']       = 'Other';
$string['resource_type:book']        = 'Book';
$string['resource_type:chart']       = 'Chart';
$string['resource_type:comic']       = 'Comic';
$string['resource_type:diary']       = 'Diary';
$string['resource_type:field_notes'] = 'Field Notes';
$string['resource_type:image']       = 'Image';
$string['resource_type:interview']   = 'Interview';
$string['resource_type:journal']     = 'Journal';
$string['resource_type:magazine']    = 'Magazine';
$string['resource_type:map']         = 'Map';
$string['resource_type:music']       = 'Music';
$string['resource_type:newspaper']   = 'Newspaper';
$string['resource_type:photograph']  = 'Photograph';
$string['resource_type:podcast']     = 'Podcast';
$string['resource_type:report']      = 'Report';
$string['resource_type:video']       = 'Video';
$string['resource_type:website']     = 'Website';

// Elements - Resource formats
$string['resource_format:none']     = 'None';
$string['resource_format:link']     = 'Link';
$string['resource_format:image']    = 'Image';
$string['resource_format:video']    = 'Video';
$string['resource_format:document'] = 'Document';

// Elements - Section groups
$string['section_group:general']  = 'General';
$string['section_group:what']     = 'What?';
$string['section_group:so_what']  = 'So What?';
$string['section_group:now_what'] = 'Now What?';
$string['section_group:extra']    = 'Extra';

// Elements - Section types
$string['section_type:text'] = 'Text';
