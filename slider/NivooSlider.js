/*
---

script: NivooSlider.js

description: A nice image slider for MooTools.

license: MIT-style license

authors:
- Johannes Fischer

requires:
- core/1.3: '*'

provides:
- NivooSlider

...
 */

var NivooSlider = new Class({

    Implements: [Events,Options],

    caption: null,
	children: null,
    containerSize: 0,
	count: 0,
    currentSlide: 0,
    currentImage: '',
    effects: {
		// used for random effects
		horizontal: ['fade', 'fold', 'sliceLeftUp', 'sliceLeftDown' , 'sliceLeftRightDown', 'sliceLeftRightUp', 'sliceRightDown', 'sliceRightUp','wipeDown','wipeUp'],
		vertical: ['fade','fold','sliceDownLeft','sliceDownRight','sliceUpDownLeft','sliceUpDownRight','sliceUpLeft','sliceUpRight','wipeLeft', 'wipeRight']
	},
	holder: null,
	hover: false,
	interval: null,
    paused: false,
    running: false,
    totalSlides: 0,

    options: {
        animSpeed: 500,
        autoPlay: true,
		directionNav: true,
		directionNavHide: false,
		directionNavWidth: '20%',
        effect: 'sliceDown', // TODO allow array with multiple effects
		interval: 3000,
		orientation: 'vertical',
		pauseOnHover: true,
		slices: 15,

		// not implemented yet
		preLoadImages: false
		//onLastSlide: function(){}

        //onStart: function(){},
        //onFinish: function(){}
    },

    initialize: function(container, options)
    {
		this.container = $(container);

		this.setOptions(options);

		this.initSlider();
		this.createSlices();
		if(this.options.autoPlay)
		{
			this.play();
		}
    },
    
    /**
     * Getter
     */
    
	getImages: function()
	{
		return this.holder.getElements('img');	
	},
	
    getSlices: function()
    {
        return this.holder.getElements('.nivoo-slice');    
    },
    
	/**
	 * Setter
	 */
	
	setBackgroundImage: function()
	{
		this.holder.setStyle('background-image','url('+this.currentImage.get('src') +')');
	},
	
	setCaptionText: function(text)
	{
		this.caption.set('text', text);
	},
	
	setLink: function()
	{
		//Set active link
		var imageParent = this.currentImage.getParent();

        if(imageParent.get('tag') == 'a')
		{
			var clone = imageParent.clone(false).cloneEvents(imageParent);
			clone.replaces(this.linkHolder);
			this.linkHolder = clone;
			this.linkHolder.addClass('nivoo-link').setStyle('display', 'block');
		}
		else
		{
			this.linkHolder.setStyle('display', 'none');
		}
	},
	
	/**
	 * Create
	 */
	
    initSlider: function()
    {
		// wrap child elements
		this.holder = new Element('div.nivoo-slider-holder').adopt(this.container.getChildren()).inject(this.container);
		// wrap child elements
		this.holder = new Element('div.nivoo-slider-holder2').adopt(this.container.getChildren()).inject(this.container);
		// wrap child elements
		this.holder = new Element('div.nivoo-slider-holder3').adopt(this.container.getChildren()).inject(this.container);

        this.containerSize = this.holder.getSize();

        // Find our slider children
        this.children = this.getImages();

        this.totalSlides = this.children.length;

        this.children.setStyle('display','none');

        this.currentImage = this.children[0];

		// init LinkHolderand set link
		this.createLinkHolder();
		this.setLink();

        // Set first background
		this.holder.setStyle('background-image', 'url('+this.currentImage.get('src')+')');

		this.createCaption();

		this.showCaption();
		
		// attach pauseOnHover		
		if(this.options.pauseOnHover && this.options.autoPlay)
		{
			this.holder.addEvents({
				'mouseenter': function(){
					this.pause();
				}.bind(this),
				'mouseleave': function(){
					this.play();
				}.bind(this)
			});
		}
		
		// create directional navigation
		if(this.options.directionNav)
		{
			this.createDirectionNav();
		}
    },
	
	createCaption: function()
	{
		this.caption = new Element('p', {
			styles: {
				opacity: 0
			}
		}).inject(this.holder);

		this.caption.store('fxInstance', new Fx.Morph(this.caption, {
			duration: 200,
			wait: false
		}));
	},

	createDirectionNav: function()
	{
		var width = this.options.directionNavWidth;

		var directionNavStyles = {
			height: this.containerSize.y,
			width: width
		};

		// create container
		var leftContainer = new Element('div.direction-nav-left', {
			styles: directionNavStyles
		}).inject(this.holder);
		
		var rightContainer = new Element('div.direction-nav-right', {
			styles: directionNavStyles
		}).inject(this.holder);
		
		// create controls
		this.leftNav = new Element('a', {
			events: {
				'click': function(e){
					e.stop();
					if(this.options.autoPlay)
					{
						this.pause();
						if(!this.options.pauseOnHover)
						{
							this.play();
						}
					}
					this.previous();
				}.bind(this)	
			},
			href: '#',
			styles: {
				height: directionNavStyles.height
			}
		}).inject(leftContainer);
		
		this.rightNav = new Element('a', {
			events: {
				'click': function(e){
					e.stop();
					if(this.options.autoPlay)
					{
						this.pause();
						if(!this.options.pauseOnHover)
						{
							this.play();
						}
					}
					this.next();
				}.bind(this)	
			},
			href: '#',
			styles: {
				height: directionNavStyles.height
			}
		}).inject(rightContainer);

		if(this.options.directionNavHide)
		{
			$$(this.leftNav, this.rightNav).setStyle('opacity', 0);
			this.holder.addEvents({
				'mouseout': function(){
					$$(this.leftNav, this.rightNav).fade(0);
				}.bind(this),
				'mouseover': function(){
					$$(this.leftNav, this.rightNav).fade(1);	
				}.bind(this)
			});
		}
	},
    
	createLinkHolder: function()
	{
		this.linkHolder = new Element('a.nivoo-link', {
			href: '#'
		}).inject(this.holder);
	},
	
    createSlices: function()
    {
		// effects that need one slice only
		if(['fade','wipeLeft','wipeRight'].contains(this.options.effect))
		{
			this.options.slices = 1;
		}

		var sliceSize = {
			x: (this.containerSize.x/this.options.slices).round(),
			y: (this.containerSize.y/this.options.slices).round()
		};

        this.options.slices.each(function(i){

            var slice = new Element('div.nivoo-slice').inject(this.holder);

			var position = {
				left: this.options.orientation == 'vertical' ? sliceSize.x*i : 0,
				top: this.options.orientation == 'horizontal' ? sliceSize.y*i : 0
			};

			// set size & position
			if(this.options.orientation == 'horizontal')
			{
				var height = i == this.options.slices-1 ? this.containerSize.y-(sliceSize.y*i) : sliceSize.y;
				var width = '100%';

				slice.setStyles({
					height: height,
                    top: position.top,
                    width: width
                });
			}
			// if vertical
			else
			{
				var height = 0;
				var width = i == this.options.slices-1 ? this.containerSize.x-(sliceSize.x*i) : sliceSize.x;

				slice.setStyles({
					left: position.left,
                    width: width
                });
			}
            slice.store('fxInstance', new Fx.Morph(slice, {
                duration: this.options.animSpeed
            })).store('coordinates', Object.merge(position, {height: height, width: width}));
        }, this);
    },
    
	/**
	 * Caption
	 */
	
	hideCaption:function()
	{
		this.caption.retrieve('fxInstance').start({
			bottom: this.caption.getHeight() * -1,
			opacity: 0.5
		});
	},
	
	showCaption: function()
	{
		var title = this.currentImage.get('title');

		if(!title){
			this.hideCaption();
			return;
		}
		
		this.setCaptionText(title);

		this.caption.retrieve('fxInstance').start({
			bottom: 0,
			opacity: 1	
		});
	},
	
	/**
	 * Slide / Animations
	 */

	next: function()
	{
		this.currentSlide++;

		if(this.currentSlide == this.totalSlides)
		{
			this.currentSlide = 0;
		}

		this.slide();
	},

	pause: function()
	{
		window.clearInterval(this.interval);
	},

	play: function()
	{
		this.interval = this.next.periodical(this.options.interval, this);
	},
	
	previous: function()
	{
		if(this.options.autoPlay)
		{
			this.pause();
			if(!this.options.pauseOnHover)
			{
				this.play();
			}
		}

		this.currentSlide--;

        if(this.currentSlide < 0)
		{
			this.currentSlide = (this.totalSlides - 1);
		}

		this.slide();
	},
	
    slide: function(slideNo)
    {
		if(this.running)
		{
			return;
		}

		if(slideNo != undefined)
		{
			this.currentSlide = slideNo;
		}

        // Set currentImage
        this.currentImage = this.children[this.currentSlide];

        this.setLink();

        // Process caption
		this.showCaption();

        var slices = this.getSlices();
		var timeBuff = 0;

		//Set new slice backgrounds
		var orientation = this.options.orientation;

		// reset slices
        slices.each(function(slice){

			var coordinates =  slice.retrieve('coordinates');

            slice.setStyles({
                background: 'url('+this.currentImage.get('src')+') no-repeat -'+ coordinates.left +'px '+ coordinates.top*-1 +'px',
				bottom: '',
				height: coordinates.height,
				left: coordinates.left,
                opacity: 0,
				right: '',
				top: coordinates.top,
				width: coordinates.width
            });

			var property = orientation == 'horizontal' ? 'width' : 'height';

			slice.setStyle(property, 0);
        }, this);
    
		// fire onStart function
        this.start();
	
        // Run effects
        this.running = true;

		var effect = this.options.effect;

		if(effect == 'random')
        {
            effect = this.effects[orientation].getRandom();
        }

		// vertical effects
        if(['sliceDownRight', 'sliceDownLeft'].contains(effect))
        {
            if(effect == 'sliceDownLeft')
            {
                slices = slices.reverse();
            }

            slices.each(function(slice){
                slice.setStyle('top', 0);

                this.animate.delay(100 + timeBuff, this, [slice, {height: this.containerSize.y, opacity: 1}]);

                timeBuff+= 50;
            }, this);
        }
        else if(['sliceUpRight', 'sliceUpLeft'].contains(effect))
        {
            if(effect == 'sliceUpLeft')
            {
                slices = slices.reverse();
            }

            slices.each(function(slice){
                var fx = slice.retrieve('fxInstance');
                
                slice.setStyle('bottom', 0);

                this.animate.delay(100 + timeBuff, this, [slice, {height: this.containerSize.y, opacity: 1}]);

                timeBuff+= 50;
            }, this);
        }
        else if(['sliceUpDownRight', 'sliceUpDownLeft'].contains(effect))
        {
            if(effect == 'sliceUpDownLeft')
            {
                slices = slices.reverse();
            }

            slices.each(function(slice, i){
                if(i%2 == 0)
                {
                    slice.setStyle('top', 0);
                }
                else
                {
                    slice.setStyles({
						bottom:  0,
						top: ''
					});
                }

                this.animate.delay(100 + timeBuff, this, [slice, {height: this.containerSize.y, opacity: 1}]);

                timeBuff+= 50;
            }, this);
        }
		else if(['wipeLeft', 'wipeRight'].contains(effect))
        {
			var styles = {
				height: this.containerSize.y,
				opacity: 1,
				width: 0
			};

			if(effect == 'wipeRight')
			{
				Object.append(styles, {
					backgroundPosition: 'top right',
					left: '',
					right: 0
				});
			}
			
			var slice = slices[0];

			slice.setStyles(styles);
			this.animate(slice, {width: this.containerSize.x}, true);
        }

		// horizontal effects		
		else if(['sliceLeftUp', 'sliceLeftDown' , 'sliceRightDown', 'sliceRightUp'].contains(effect))
		{
			if(effect == 'sliceLeftUp' || effect == 'sliceRightUp')
            {
                slices = slices.reverse();
            }
			
			if(effect == 'sliceRightDown' || effect == 'sliceRightUp')
			{
				slices.setStyles({
					left: '',
					right: 0
				});
			}
			else
			{
				slices.setStyles({
					left: 0,
					right: ''
				});
			}

            slices.each(function(slice){    
                this.animate.delay(100 + timeBuff, this, [slice, {opacity: 1, width: this.containerSize.x}]);

                timeBuff+= 50;
            }, this);
		}
		else if(['sliceLeftRightDown', 'sliceLeftRightUp'].contains(effect))
        {
            if(effect == 'sliceLeftRightUp')
            {
                slices = slices.reverse();
            }

            slices.each(function(slice, i){
                if(i%2 == 0)
                {
                    slice.setStyles({
						left: 0,
						right: ''
					});
                }
                else
                {
                    slice.setStyles({
						left: '',
						right: 0
					});
                }

                this.animate.delay(100 + timeBuff, this, [slice, {opacity: 1, width: this.containerSize.x}]);

                timeBuff+= 50;
            }, this);
        }
		else if(['wipeDown', 'wipeUp'].contains(effect))
        {
			var styles = {
				height: 0,
				opacity: 1,
				width: this.containerSize.x
			};

			if(effect == 'wipeUp')
			{
				Object.append(styles, {
					backgroundPosition: 'bottom left',
					bottom: 0,
					top: ''
				});
			}
			
			var slice = slices[0];

			slice.setStyles(styles);
			this.animate(slice, {height: this.containerSize.y}, true);
        }

		// horizontal or vertical		
        else if(effect == 'fold')
        {
            slices.each(function(slice){
				var fxStyles = {
					opacity: 1	
				};
				if(orientation == 'horizontal')
				{
					fxStyles.height = slice.getHeight();
	
					slice.setStyles({
						height: 0,
						width: this.containerSize.x
					});
				}
				else
				{
					fxStyles.width = slice.getWidth();
	
					slice.setStyles({
						height: this.containerSize.y,
						top: 0,
						width: 0
					});
				}

				this.animate.delay(100 + timeBuff, this, [slice, fxStyles]);				
                timeBuff+= 50;
            }, this);
        }
        else  // if(effect == 'fade')
        {
			var slice = slices[0];

			slice.setStyles({
				height: this.containerSize.y,
				width: this.containerSize.x	
			});

            this.animate(slice, {opacity: 1}, true);
        }
    },
    
    animate: function(slice, fxStyles, last)
    {
        var fx = slice.retrieve('fxInstance');
		
		var isLast = last != undefined && last == true;

        fx.start(fxStyles).chain(function(){
			this.count++;
			if(this.count == this.options.slices || isLast)
			{
				this.running = false;

				// fire onFinish function
				this.finish();

				this.setBackgroundImage();
	
				this.count = 0;
			}
		}.bind(this));		
    },
    
    /**
     * Events
     */
    
    finish: function()
    {
        this.fireEvent('finish');
    },

    start: function()
    {
        this.fireEvent('start');
    }

});