MotionCAPTCHA is a jQuery CAPTCHA plugin that requires users to sketch the shape they see in the canvas in order to submit a form.

At the moment, it's just a proof-of-concept, (no IE support) but the next releases will see progressive enhancement and the ability to use this in production environments as a serious CAPTCHA alternative.

### [Demo](http://josscrowcroft.com/demos/motioncaptcha/ "MotionCAPTCHA Demo") &bull; [Homepage / Screenshots](http://josscrowcroft.com/projects/motioncaptcha-jquery-plugin/ "MotionCAPTCHA - Joss Crowcroft") 

Don't try to use MotionCAPTCHA v0.2 in production - watch and wait for the full release!

**Update 22-08-11:** For everyone who's been watching, my apologies for not updating you sooner. The response to MotionCATPCHA has been amazing, and I still have big plans for it, but work and other distractions got in the way - to everyone who still wants to see MotionCATPCHA become fully-fledged and being used on live sites and apps, watch out for a codesmash day in September (possibly the 10th!)

In the meantime, please try it out, suggest features and report bugs - if you want to help out turning this thing into a reality, fork away.


## How It Works

In v1.0, the plugin will use progressive enhancement so that the form uses a standard server-side (e.g. PHP) CAPTCHA script, and then on page load, the plugin switches that for the MotionCAPTCHA canvas, only if the browser is grown up enough.

For now, here's how it works (if you need a step-by-step guide, there's a **How-To** below):

* You manually disable your form, by emptying the form's `action` attribute, and placing its value in a hidden `<input>` with a specific ID. You should also put `disabled="disabled"` on the submit button, for added points.
* You add a few HTML lines to your form to initialise the MotionCAPTCHA canvas, and add the plugin's scripts to your page.
* The user draws the shape and, if it checks out, the plugin inserts the form's `action` into the `<form>` tag. The user can submit the form, happy days.

I know this is going to be unpopular in its current state, because you're making it impossible for people to submit the form without JavaScript or Canvas support - BUT, who gives a crap, it's fun. And in future, it'll degrade gracefully, etc etc.


## Technology / Credits

The MotionCAPTCHA plugin combines one of the brushes ('Ribbon' - and a lot of the logic) from [Mr Doob's Harmony canvas experiment](http://mrdoob.com/projects/harmony/), with gesture recognition algorithms and vector shapes based entirely on the [$1 Unistroke Gesture Recognizer](http://depts.washington.edu/aimgroup/proj/dollar/) by Jacob O. Wobbrock, Ph.D. and Andrew D. Wilson, Ph.D., and the later [Protractor algorithm](http://www.yangl.org/pdf/protractor-chi2010.pdf) improvement by Yang Li, Ph.D. 

The idea originally came from [Paul Irish's blog](http://www.paulirish.com), where he combined the Ribbon brush and the Unistroke recognizer on the background of his site - you get a pretty special easter-egg if you draw a star. Great song.

In my book, all of these people are ace - I just borrowed from them and spliced their works into one unholy mess (actually it's fairly tidy, though I'm hoping people will suggest performance improvements.)


## Quick Roadmap

### v0.3
* Try to fix up IE support via excanvas or similar library
* Add a real functionality for a 'new shape' button (a shape-switcharoo method)

### v1.0
* a) Combine with a simple PHP CAPTCHA and server-side checking script, for progressive enhancement. By default, form is submitted with regular CAPTCHA. On page load, if browser supports JavaScript and HTML5 Canvas, regular CAPTCHA is switched for mighty MotionCAPTCHA, and that would be checked on the server instead.
* b) See (a)

### v1.1
* Perform (or add option/scripts to perform) server-side validation of the vector shape drawn by the user. Thought this was in the roadmap already (thanks [RobIII](http://robiii.nl "RobIII") for pointing out!)


## Changelog

### v0.2
* Added mobile support for current functionality. Should work in Android and iOS (iPhones/iPads) now.
* A few speed improvements and code cleanups.


## How To Use

1. Add the plugin scripts: (I'm using jQuery 1.6 from the google API, but you could load it locally - and MotionCAPTCHA is supported down to jQuery 1.4):

    
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
        <script src="jquery.motionCaptcha.0.2.min.js"></script>
        <link href="jquery.motionCaptcha.0.2.css"></script>

2. Code the form as usual, with a unique ID (eg. '#mycoolform') and set the form action to blank (or '#') - eg:

        <form action="#" id="mycoolform" method="[get/post]">

3. Add this placeholder `<div>` element to your form (NB. use `<fieldset>`s if you need it to validate) containing the blank canvas:

        <div id="mc">
            <p>Please draw the shape in the box to submit the form:</p>
            <canvas id="mc-canvas"></canvas>
        </div>

4. Disable the submit button, eg:

        <input type="submit" disabled="disabled" value="Submit Form" />

5. Add a hidden input to the form, with the form action as its value. Give it either a unique id, or the id 'mc-action', like so:

        <input type="hidden" id="fairly-unique-id" value="submitform.php" />

6. Call the plugin on the form element. If you used any other ID than 'mc-action' for the hidden input, you'll just need to pass it to the plugin, like this:

        $('#form-id').motionCaptcha({
            action: '#fairly-unique-id'
        });
        
        // Or, if you just used 'mc-action' as the hidden input's ID:
        $('#form-id').motionCaptcha();

7. Other options are available (defaults are shown):

        $('#form-id').motioncaptcha({
            // Basics:
            action: '#mc-action',        // the ID of the input containing the form action
            divId: '#mc',                // if you use an ID other than '#mc' for the placeholder, pass it in here
            cssClass: '.mc-active',      // this CSS class is applied to the 'mc' div when the plugin is active
            canvasId: '#mc-canvas',      // the ID of the MotionCAPTCHA canvas element
            
            // An array of shape names that you want MotionCAPTCHA to use:
            shapes: ['triangle', 'x', 'rectangle', 'circle', 'check', 'caret', 'zigzag', 'arrow', 'leftbracket', 'rightbracket', 'v', 'delete', 'star', 'pigtail'],
            
            // These messages are displayed inside the canvas after a user finishes drawing:
            errorMsg: 'Please try again.',
            successMsg: 'Captcha passed!',
            
            // This message is displayed if the user's browser doesn't support canvas:
            noCanvasMsg: "Your browser doesn't support <canvas> - try Chrome, FF4, Safari or IE9."
            
            // This could be any HTML string (eg. '<label>Draw this shit yo:</label>'):
            label: '<p>Please draw the shape in the box to submit the form:</p>'
        });
