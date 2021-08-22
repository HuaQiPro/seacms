(function($) {
	var s = {
		wrapContent: "<div class='jQuery-imageUpload'>",
		inputFileName: "inputFile",
		inputFileClass: "inputFile",
		uploadButtonValue: "Upload",
		uploadButtonClass: "uploadButton",
		browseButtonValue: "Browse",
		browseButtonClass: "browseButton",
		deleteButtonValue: "Delete image",
		deleteButtonClass: "deleteButton",
		automaticUpload: false,
		formClass: "controlForm",
		hideFileInput: true,
		hideDeleteButton: false,
		hover: true,
		addClass: "jQuery-image-upload"
	};
	$.fn.imageUpload = function(f) {
		var g = this;
		if(!g.length) {
			return g
		}
		var i = $.extend(s, f);
		if(g.length > 1) {
			g.each(function() {
				$(this).imageUpload(i)
			});
			return g
		}
		if(g.data("imageUpload")) {
			g.trigger("imageUpload.reload");
			return g
		}
		g.addClass(i.addClass);
		g.data("imageUpload", f);
		if(!i.formAction) {
			throw new Error("Form action was not provided. Please provide it: $(...).imageUpload({formAction: '...'})")
		}
		if(!i.hover) {
			g.wrap(i.wrapContent)
		}
		var j = $("<div>").addClass("controls");
		var k = $("<input>").attr({
			type: "file",
			name: i.inputFileName
		}).addClass(i.inputFileClass);
		var l = $("<button>").attr("type", "submit").addClass(i.uploadButtonClass).html(i.uploadButtonValue);
		var m = $("<button>").addClass(i.browseButtonClass).html(i.browseButtonValue).on("click", function() {
			k.click();
			return false
		});
		var n = $("<button>").addClass(i.deleteButtonClass).html(i.deleteButtonValue).on("click", function() {
			g.trigger("imageUpload.destroy");
			g.trigger("imageUpload.imageRemoved");
			g.remove();
			return false
		});
		var p = "uploadIframe-" + Math.random().toString(36).substring(5, 20).toLowerCase();
		var q = $("<iframe>").attr({
			id: p,
			name: p
		}).hide();
		var r = $("<form>").addClass(i.formClass).attr({
			target: q.attr("id"),
			enctype: "multipart/form-data",
			method: "post",
			action: i.formAction
		});
		r.append([m, k, l, n, q]);
		if(i.hideDeleteButton) {
			n.remove()
		}
		if(i.automaticUpload) {
			l.hide();
			k.on("change", function() {
				if(!$(this).val()) {
					return
				}
				l.click()
			})
		}
		if(i.hideFileInput) {
			k.hide()
		} else {
			m.hide()
		}
		j.append(r);
		r.on("submit", function() {
			var c = $(this);
			q.off("load");
			var d = g.attr("src");
			if(typeof i.waiter === "string") {
				g.attr("src", i.waiter)
			}
			g.addClass("loading");
			j.hide();
			q.on("load", function() {
				var a = $(this.contentWindow.document).text();
				var b;
				try {
					b = eval("(" + a + ")");
				} catch(e) {}
				if(b == undefined) {
					loadImage(g, d);
					g.trigger("imageUpload.uploadFailed", ['返回数据格式不正确']);
					return;
				}
				if(b.code == 0) {
					loadImage(g, d);
					g.trigger("imageUpload.uploadFailed", [b.msg]);
					return;
				}
				if(i.hideFileInput) {
					g.trigger("imageUpload.reload")
				}
				if(!k.val()) {
					loadImage(g, d);
					return
				}
				q.attr("src", "");
				loadImage(g, b.file, function() {
					g.trigger("imageUpload.imageChanged")
				});
				k.replaceWith(k.clone(true))
			})
		});
		if(!i.hover) {
			g.parent().append(j)
		} else {
			j.css({
				position: "absolute"
			});
			j.addClass("jQuery-image-upload-controls");
			$(".fed-user-image").append(j.hide());
			g.on("mouseenter", function() {
				if(g.hasClass("loading")) {
					return
				}
				j.css({
					top: 0,
					left: 0
				});
				j.show()
			});
			$('.fed-user-image').mouseleave(function() {
				j.hide()
			});
		}
		g.on("imageUpload.destroy", function() {
			j.remove();
			g.off("imageUpload.destroy");
			g.off("imageUpload.reload");
			g.data("imageUpload", null)
		});
		g.on("imageUpload.reload", function() {
			g.trigger("imageUpload.destroy");
			g.imageUpload(f)
		});
		return g
	};

	function loadImage(a, b, c) {
		a.fadeOut(function() {
			a.attr("src", b);
			imgLoad(a, function() {
				a.removeClass("loading");
				a.fadeIn();
				if(typeof c === "function") {
					c()
				}
			})
		})
	}

	function imgLoad(a, b) {
		$(a).each(function() {
			if(this.complete) {
				b.apply(this)
			} else {
				$(this).on("load", function() {
					b.apply(this)
				})
			}
		})
	}
	$.imageUpload = $.fn.imageUpload;
	$.imageUpload.defaults = s
})($);