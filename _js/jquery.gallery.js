function Gallery(element, options) {
	this.options = options = $.extend({}, Gallery.OPTIONS, options);

	this.element = $(element);
	this.slides = this.element.find(options.slideSelector);

	this._initLayout();

	if (options.pauseOnHover) {
		this.element.on({
			mouseenter: $.proxy(function () {
				if (this.autoPlay) {
					this.stop();
					this.paused = true;
				}
			}, this),
			mouseleave: $.proxy(function () {
				if (this.paused) {
					this.start();
					this.paused = false;
				}
			}, this)
		}, typeof options.pauseOnHover == 'string' ? options.pauseOnHover : '');
	}

	this.show(options.startIndex, 'show');

	if (options.autoPlay) {
		this.start();
	}
}

Gallery.OPTIONS = {
	slideSelector: '> *',
	transition: 'fade',
	speed: 400,
	timeout: 6000,
	pauseOnHover: false,
	autoPlay: false,
	startIndex: 0,
	wrapAround: true
};

Gallery.TRANSITIONS = {
	show: function (nextIndex, currentIndex, slides, speed) {
		slides.eq(currentIndex).hide();
		slides.eq(nextIndex).show();
	},
	fade: function (nextIndex, currentIndex, slides, speed) {
		slides.eq(nextIndex).css('z-index', 0).show();
		return slides.eq(currentIndex).css('z-index', 1).fadeOut(speed).promise();
	},
	'slide-horizontal': function (nextIndex, currentIndex, slides, speed) {
		var currentSlide = slides.eq(currentIndex),
			nextSlide = slides.eq(nextIndex),
			width = slides.eq(currentIndex).outerWidth(),
			forward = nextIndex > currentIndex;

		currentSlide.css('left', 0);
		nextSlide.css('left', forward ? width : -width).show();
		return currentSlide.add(nextSlide).animate({ left: (forward ? '-=' : '+=') + width }, speed).promise().then(function () {
			currentSlide.hide().css('left', 0);
		});
	},
	'slide-vertical': function (nextIndex, currentIndex, slides, speed) {
		var currentSlide = slides.eq(currentIndex),
			nextSlide = slides.eq(nextIndex),
			height = slides.eq(currentIndex).outerHeight(),
			forward = nextIndex > currentIndex;

		currentSlide.css('top', 0);
		nextSlide.css('top', forward ? height : -height).show();
		return currentSlide.add(nextSlide).animate({ top: (forward ? '-=' : '+=') + height }, speed).promise().then(function () {
			currentSlide.hide().css('top', 0);
		});
	}
};

Gallery.prototype = {
	_initLayout: function () {
		var element = this.element,
			slides = this.slides;

		if (element.css('position') == 'static') {
			element.css('position', 'relative');
		}

		element.css({ overflow: 'hidden', minHeight: Math.max.apply(null, slides.map(function () {
			return $(this).outerHeight();
		}).toArray()) });

		slides.css({ position: 'absolute', left: 0, top: 0, zIndex: 0 });
		slides.hide();
	},

	setCurrentIndex: function (index, transition) {
		var oldIndex = this.currentIndex,
			length = this.slides.length;

		if (this.options.wrapAround) {
			index = index % length;

			if (index < 0) {
				index = length - index;
			}
		}

		index = Math.min(Math.max(index, 0), length - 1);

		if (index == oldIndex) {
			return false;
		}

		var eventArgs = [index, oldIndex, this.slides.stop(true, true)];

		this.element.triggerHandler('beforeChange', eventArgs);

		this.currentIndex = index;

		var promise = (typeof transition == 'function' && transition.apply(null, eventArgs.concat(this.options.speed))) || $.Deferred().resolve();
		promise.always($.proxy(this.element, 'triggerHandler', 'afterChange', eventArgs));
	},

	show: function (index, transition) {
		if (typeof transition === 'undefined') {
			transition = this.options.transition;
		}

		if (typeof transition !== 'function' && transition !== false) {
			transition = Gallery.TRANSITIONS[transition] || Gallery.TRANSITIONS.show;
		}

		this.setCurrentIndex(index, transition);

		if (this.autoPlay) {
			this.start();
		}
	},

	next: function (transition) {
		this.show(this.currentIndex + 1, transition);
	},
	previous: function (transition) {
		this.show(this.currentIndex - 1, transition);
	},

	start: function () {
		this.stop();
		this.autoPlay = true;
		this.timer = setTimeout($.proxy(function () {
			this.next();
			this.start();
		}, this), this.options.timeout);
	},
	stop: function () {
		this.autoPlay = false;
		this.paused = false;
		clearTimeout(this.timer);
	}
};


function PanGestureRecognizer(element, delegate) {
	this.element = element;
	
	if (!element.addEventListener) {
	    element.attachEvent("touchstart", this);
	}
	else {
	    element.addEventListener('touchstart', this, false);
	}


	// element.style.webkitUserSelect = 'none';
	
	this.delegate = delegate;
	
	this.enabled = true;
	this.panning = false;
	this.touchPoints = [];
}

PanGestureRecognizer.PANNING_THRESHOLD = 10;
PanGestureRecognizer.VELOCITY_TIME = 100;

PanGestureRecognizer.prototype = {
	notifyDelegate: function (name) {
		if (this.delegate && typeof this.delegate[name] == 'function') {
			return this.delegate[name].apply(this.delegate, Array.prototype.slice.call(arguments, 1));
		}
	},
	
	handleEvent: function (event) {
		this[event.type](event);
	},
	
	touchstart: function (e) {
		if (!this.enabled) {
			this.touchcancel();
			return;
		}
		
		if (e.touches.length > 1) {
			this.touchcancel();
			return;
		}
		
		this.element.addEventListener('touchmove', this, false);
		this.element.addEventListener('touchend', this, false);
		this.element.addEventListener('touchcancel', this, false);
		
		this.startTouchPoint = this.addTouchPoint(e);
	},
	touchmove: function (e) {
		if (!this.enabled) {
			this.touchcancel();
			return;
		}
		
		var touch = this.lastTouchPoint = this.addTouchPoint(e);
		
		this.translation = {
			x: touch.x - this.startTouchPoint.x,
			y: touch.y - this.startTouchPoint.y
		};

		if (!this.panning) {
			var xThresholdReached = Math.abs(this.translation.x) >= PanGestureRecognizer.PANNING_THRESHOLD;
			var yThresholdReached = Math.abs(this.translation.y) >= PanGestureRecognizer.PANNING_THRESHOLD;
			var direction = this.delegate.direction;
			
			if ((xThresholdReached && direction != 'vertical') || (yThresholdReached && direction != 'horizontal')) {
				this.panning = true;
				e.preventDefault();
				
				if (this.touchPoints.length == 2) {
					this.notifyDelegate('panstart', this.startTouchPoint, this.translation);
					this.notifyDelegate('panmove', touch, this.translation);
				} else {
					this.notifyDelegate('panstart', touch, this.translation);
				}
			} else if (xThresholdReached || yThresholdReached) {
				this.touchcancel();
			}
		} else {
			this.notifyDelegate('panmove', touch, this.translation);
		}
	},
	touchend: function (e) {
		if (this.panning) {
			this.notifyDelegate('panend', this.lastTouchPoint, this.translation, this.getTouchPointVelocity(e.timeStamp));
		}

		this.reset();
	},
	touchcancel: function (e) {
		if (this.panning) {
			this.notifyDelegate('pancancel', this.lastTouchPoint, this.translation);
		}

		this.reset();
	},
	
	reset: function () {
		this.panning = false;
		this.touchPoints = [];
		
		this.element.removeEventListener('touchmove', this, false);
		this.element.removeEventListener('touchend', this, false);
		this.element.removeEventListener('touchcancel', this, false);
	},
	
	enable: function (enable) {
		if (enable == this.enabled) {
			return;
		}
		
		this.enabled = enable;
		
		if (!enable) {
			this.touchcancel();
		}
	},
	
	addTouchPoint: function (event) {
		var point = {
			x: event.touches[0].pageX,
			y: event.touches[0].pageY,
			timeStamp: event.timeStamp
		};
		
		this.touchPoints.push(point);
		
		return point;
	},
	getTouchPointVelocity: function (timestamp) {
		timestamp = (timestamp || new Date().getTime()) - PanGestureRecognizer.VELOCITY_TIME;
		
		var touchPoints = [];
		
		for (var i = this.touchPoints.length - 1; i >= 0 && this.touchPoints[i].timeStamp >= timestamp; i--) {
			touchPoints.push(this.touchPoints[i]);
		}
		
		if (touchPoints.length < 2) {
			return { x: 0, y: 0 };
		}
		
		var touchPointStart = touchPoints[0];
		var touchPointEnd = touchPoints[touchPoints.length - 1];
		var time = (touchPointEnd.timeStamp - touchPointStart.timeStamp) / 1000;
		
		return {
			x: (touchPointEnd.x - touchPointStart.x) / time,
			y: (touchPointEnd.y - touchPointStart.y) / time
		};
	}
};
