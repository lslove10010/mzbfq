var YKQ = {
	versions: function() {
		var u = navigator.userAgent,
			app = navigator.appVersion;
		return {
			trident: u.indexOf('Trident') > -1, //IE内核
			presto: u.indexOf('Presto') > -1, //opera内核
			webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
			gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
			mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
			ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
			android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
			iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
			iPad: u.indexOf('iPad') > -1, //是否iPad
			webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
			weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
			qq: u.match(/\sQQ/i) == " qq" //是否QQ
		};
	}(),
	'start': function() {
		$.ajax({
			url: "/admin/api.php",
			dataType: "json",
			success: function(e) {
				YKQ.waittime = e.data.waittime
				YKQ.ads = e.data.ads;
				config.logo = e.data.logo;
				up.pbgjz = e.data.pbgjz;
				up.trysee = e.data.trytime;
				config.sendtime = e.data.sendtime;
				config.color = e.data.color;
				//config.av = e.data.av;
				//config.api = e.data.api;
			//	config.next = e.data.next;
				//config.group_x = YKQ.ads.set.group;
				config.dmrule = e.data.dmrule;
				//config.group = YKQ.getCookie('group_id');
				danmuon = e.data.danmuon;
                if(YKQ.ads.state=='on'){
					if (YKQ.ads.set.state == '1') {
						YKQ.MYad.vod(YKQ.ads.set.vod.url, YKQ.ads.set.vod.link);
					} else if (YKQ.ads.set.state == '2') {
						YKQ.MYad.pic(YKQ.ads.set.pic.link, YKQ.ads.set.pic.time, YKQ.ads.set.pic.img);
					}
				} else {
					YKQ.play(rc4(config.url,'202205051426239465',1));
				}
			}
		});
	},
	'play': function(url) {
		if (!danmuon) {
			YKQ.player.play(url);
		} else {
			if (config.av != '') {
				YKQ.player.bdplay(url);
			} else {
				YKQ.player.dmplay(url);
			}
		}
		$(function() {
			$(".yzmplayer-setting-speeds,.yzmplayer-setting-speed-item").on("click", function() {
				$(".speed-stting").toggleClass("speed-stting-open");
			});
			$(".speed-stting .yzmplayer-setting-speed-item").click(function() {
				$(".yzmplayer-setting-speeds  .title").text($(this).text());
			});
		});
		$(".yzmplayer-fulloff-icon").on("click", function() {
			YKQ.dp.fullScreen.cancel();
		});
		$(".yzmplayer-showing").on("click", function() {
			YKQ.dp.play();
			$(".vod-pic").remove();
		});
		if (config.title != '') {
			$("#vodtitle").html(config.title + '  ' + config.sid);
		};

	},
	'dmid': function() {
		if (up.diyid[0] == 0 && config.id != '') {
			a = config.id,
				b = config.sid
		} else if (up.diyid[0] == 1 || !config.id) {
			a = up.diyid[1],
				b = up.diyid[2]
		}
		YKQ.id = a + ' P' + b
	},
	'load': function() {
		setTimeout(function() {
			$("#link1").fadeIn();
		}, 100);
		setTimeout(function() {
			$("#link1-success").fadeIn();
		}, 500);
		setTimeout(function() {
			$("#link2").show();
		}, 1 * 1000);
		setTimeout(function() {
			$("#link3,#span").fadeIn();
		}, 2 * 1000);
		if (YKQ.versions.weixin && (YKQ.versions.ios || YKQ.versions.iPad)) {
			var css = '<style type="text/css">';
			css += '#loading-box{display: none;}';
			css += '</style>';
			$('body').append(css).addClass("");

		}
		YKQ.danmu.send();
		YKQ.danmu.list();
		YKQ.def();
		YKQ.video.try();
		YKQ.dp.danmaku.opacity(1);
	},
	'def': function() {
		console.log('播放器开启');
		YKQ.stime = 0;
		YKQ.headt = yzmck.get("headt");
		YKQ.lastt = yzmck.get("lastt");
		YKQ.last_tip = parseInt(YKQ.lastt) + 10;
		YKQ.frists = yzmck.get('frists');
		YKQ.lasts = yzmck.get('lasts');
		YKQ.playtime = Number(YKQ.getCookie("time_" + config.url));
		YKQ.ctime = YKQ.formatTime(YKQ.playtime);
		YKQ.dp.on("loadedmetadata", function() {
			YKQ.loadedmetadataHandler();
		});
		YKQ.dp.on("ended", function() {
			YKQ.endedHandler();
		});
		YKQ.dp.on('pause', function() {
			YKQ.MYad.pause.play(YKQ.ads.pause.link, YKQ.ads.pause.pic);
		});
		YKQ.dp.on('play', function() {
			YKQ.MYad.pause.out();
		});
		YKQ.dp.on('timeupdate', function(e) {
			YKQ.timeupdateHandler();
		});
		YKQ.jump.def()

	},
	'video': {
		'play': function() {
			$("#link3").text("视频已准备就绪，即将为您播放");
			setTimeout(function() {
				YKQ.dp.play();
				$("#my-loading", parent.document).remove();
				YKQ.jump.head();
			}, 0);
		},
		'next': function() {
			top.location.href = up.mylink + config.next;
		},
		'try': function() {
			if (up.trysee > 0 && config.group < config.group_x && config.group != '') {
				$('#dmtext').attr({
					"disabled": true,
					"placeholder": "登陆后才能发弹幕yo(・ω・)"
				});
				setInterval(function() {
					var t = up.trysee * 60;
					var s = YKQ.dp.video.currentTime;
					if (s > t) {
						YKQ.dp.video.currentTime = 0;
						YKQ.dp.pause();
						layer.confirm(up.trysee + "分钟试看已结束，请登录继续播放完整视频", {
							anim: 1,
							title: '温馨提示',
							btn: ['登录', '注册'],
							yes: function(index, layero) {
								top.location.href = up.mylink + "/index.php/user/login.html";
							},
							btn2: function(index, layero) {
								top.location.href = up.mylink + "/index.php/user/reg.html";
							}
						});
					}
				}, 1000);
			};
		},
		'seek': function() {
			YKQ.dp.seek(YKQ.playtime);
		},
		'end': function() {
			layer.msg("播放结束啦=。=");
		},
		'con_play': function() {
			if (!danmuon) {
				YKQ.jump.head();
			} else {
				var conplayer =
					` <e>已播放至${YKQ.ctime}，继续上次播放？</e><d class="conplay-jump">是 <i id="num">${YKQ.waittime}</i>s</d><d class="conplaying">否</d>`
				$("#link3").html(conplayer);
				var span = document.getElementById("num");
				var num = span.innerHTML;
				var timer = null;
				setTimeout(function() {
					timer = setInterval(function() {
						num--;
						span.innerHTML = num;
						if (num == 0) {
							clearInterval(timer);
							YKQ.video.seek();
							YKQ.dp.play();
							$(".memory-play-wrap,#loading-box").remove();
						}
					}, 1000);
				}, 1);
			};
			var cplayer =
				`<div class="memory-play-wrap"><div class="memory-play"><span class="close">×</span><span>上次看到 </span><span>${YKQ.ctime}</span><span class="play-jump">跳转播放</span></div></div>`
			$(".yzmplayer-cplayer").append(cplayer);
			$("#my-loading", parent.document).remove();
			YKQ.dp.play();
			$(".close").on("click", function() {
				$(".memory-play-wrap").remove();
			});
			setTimeout(function() {
				$(".memory-play-wrap").remove();
			}, 20 * 1000);
			$(".conplaying").on("click", function() {
				clearTimeout(timer);
				$("#loading-box").remove();
				YKQ.dp.play();
				YKQ.jump.head();
			});
			$(".conplay-jump,.play-jump").on("click", function() {
				clearTimeout(timer);
				YKQ.video.seek();
				$(".memory-play-wrap,#loading-box").remove();
				YKQ.dp.play();
			});

		}
	},
	'jump': {
		'def': function() {
			h = ".yzmplayer-setting-jfrist label";
			l = ".yzmplayer-setting-jlast label";
			f = "#fristtime";
			j = "#jumptime";
			a(h, 'frists', YKQ.frists, 'headt', YKQ.headt, f);
			a(l, 'lasts', YKQ.lasts, 'lastt', YKQ.lastt, j);

			function er() {
				layer.msg("请输入有效时间哟！");
			}

			function su() {
				layer.msg("设置完成，将在刷新或下一集生效");
			}

			function a(b, c, d, e, g, t) {
				$(b).on("click", function() {
					o = $(t).val();
					if (o > 0) {
						$(b).toggleClass('checked');
						su();
						g = $(t).val();
						yzmck.set(e, g);
					} else {
						er()
					};
				});
				if (d == 1) {
					$(b).addClass('checked');
					$(b).click(function() {
						o = $(t).val();
						if (o > 0) {
							yzmck.set(c, 0);
						} else {
							er()
						};
					});
				} else {
					$(b).click(function() {
						o = $(t).val();
						if (o > 0) {
							yzmck.set(c, 1);
						} else {
							er()
						};
					});
				}
			};
			$(f).attr({
				"value": YKQ.headt
			});
			$(j).attr({
				"value": YKQ.lastt
			});
			YKQ.jump.last();
		},
		'head': function() {
			if (YKQ.stime > YKQ.playtime) YKQ.playtime = YKQ.stime;
			if (YKQ.frists == 1) {
				if (YKQ.headt > YKQ.playtime || YKQ.playtime == 0) {
					YKQ.jump_f = 1
				} else {
					YKQ.jump_f = 0
				}
			}
			if (YKQ.jump_f == 1) {
				YKQ.dp.seek(YKQ.headt);
				YKQ.dp.notice("已为您跳过片头");
			}
		},
		'last': function() {
			if (config.next != '') {
				if (YKQ.lasts == 1) {
					setInterval(function() {
						var e = YKQ.dp.video.duration - YKQ.dp.video.currentTime;
						if (e < YKQ.last_tip) YKQ.dp.notice('即将为您跳过片尾');
						if (YKQ.lastt > 0 && e < YKQ.lastt) {
							YKQ.setCookie("time_" + config.url, "", -1);
							YKQ.video.next();
						};
					}, 1000);
				};
			} else {
				$(".icon-xj").remove();
			};
		},
		'ad': function(a, b) {}
	},
	'danmu': {
		'send': function() {
			g = $(".yzm-yzmplayer-send-icon");
			d = $("#dmtext");
			h = ".yzmplayer-comment-setting-";
			$(h + "color input").on("click", function() {
				r = $(this).attr("value");
				setTimeout(function() {
					d.css({
						"color": r
					});
				}, 100);
			});
			$(h + "type input").on("click", function() {
				t = $(this).attr("value");
				setTimeout(function() {
					d.attr("dmtype", t);
				}, 100);
			});

			$(h + "font input").on("click", function() {
				if (up.trysee > 0 && config.group == config.group_x) {
					layer.msg("会员专属功能");
					return;
				};
				t = $(this).attr("value");
				setTimeout(function() {
					d.attr("size", t);
				}, 100);
			});
			g.on("click", function() {
				a = document.getElementById("dmtext");
				a = a.value;
				b = d.attr("dmtype");
				c = d.css("color");
				z = d.attr("size");
				if (up.trysee > 0 && config.group < config.group_x && config.group != '') {
					layer.msg("登陆后才能发弹幕yo(・ω・)");
					return;
				}
				for (var i = 0; i < up.pbgjz.length; i++) {
					if (a.search(up.pbgjz[i]) != -1) {
						layer.msg("请勿发送无意义内容，规范您的弹幕内容");
						return;
					}
				}
				if (a.length < 1) {
					layer.msg("要输入弹幕内容啊喂！");
					return;
				}
				var e = Date.parse(new Date());
				var f = yzmck.get('dmsent', e);
				if (e - f < config.sendtime * 1000) {
					layer.msg('请勿频繁操作！发送弹幕需间隔' + config.sendtime + '秒~');
					return;
				}
				d.val("");
				YKQ.dp.danmaku.send({
					text: a,
					color: c,
					type: b,
					size: z
				});
				yzmck.set('dmsent', e);
			});

			function k() {
				g.trigger("click");
			};
			d.keydown(function(e) {
				if (e.keyCode == 13) {
					k();
				};
			});
		},
		'list': function() {
			$(".yzmplayer-list-icon,.yzm-yzmplayer-send-icon").on("click", function() {
				$(".list-show").empty();
				$.ajax({
					url: config.api + "?ac=get&id=" + YKQ.id,
					success: function(d) {
						if (d.code == 23) {
							a = d.danmuku;
							b = d.name;
							c = d.danum;
							$(".danmuku-num").text(c)
							$(a).each(function(index, item) {
								l =
									`<d class="danmuku-list" time="${item[0]}"><li>${YKQ.formatTime(item[0])}</li><li title="${item[4]}">${item[4]}</li><li title="用户：${item[3]}  IP地址：${item[5]}">${item[6]}</li><li class="report" onclick="YKQ.danmu.report(\'${item[5]}\',\'${b}\',\'${item[4]}\',\'${item[3]}\')">举报</li></d>`
								$(".list-show").append(l);
							})
						}
						$(".danmuku-list").on("dblclick", function() {
							YKQ.dp.seek($(this).attr("time"))
						})
					}
				});
			});
var liyih = '<div class="dmrules"><a target="_blank" href="' + config.dmrule + '">弹幕礼仪 </a></div>';
			$("div.yzmplayer-comment-box:last").append(liyih);
			$(".yzmplayer-watching-number").text(up.usernum);
			$(".yzmplayer-info-panel-item-title-amount .yzmplayer-info-panel-item-title").html("违规词");
			for (var i = 0; i < up.pbgjz.length; i++) {
				var gjz_html = "<e>" + up.pbgjz[i] + "</e>";
				$("#vod-title").append(gjz_html);
			}
			add('.yzmplayer-list-icon', ".yzmplayer-danmu", 'show');

			function add(div1, div2, div3, div4) {
				$(div1).click(function() {
					$(div2).toggleClass(div3);
					$(div4).remove();
				});
			}
		},
		'report': function(a, b, c, d) {
			layer.confirm('' + c + '<!--br><br><span style="color:#333">请选择需要举报的类型</span-->', {
				anim: 1,
				title: '举报弹幕',
				btn: ['违法违禁', '色情低俗', '恶意刷屏', '赌博诈骗', '人身攻击', '侵犯隐私', '垃圾广告', '剧透', '引战'],
				btn3: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '恶意刷屏');
				},
				btn4: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '赌博诈骗');
				},
				btn5: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '人身攻击');
				},
				btn6: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '侵犯隐私');
				},
				btn7: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '垃圾广告');
				},
				btn8: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '剧透');
				},
				btn9: function(index, layero) {
					YKQ.danmu.post_r(a, b, c, d, '引战');
				}
			}, function(index, layero) {
				YKQ.danmu.post_r(a, b, c, d, '违法违禁');
			}, function(index) {
				YKQ.danmu.post_r(a, b, c, d, '色情低俗');
			});
		},
		'post_r': function(a, b, c, d, type) {
			$.ajax({
				type: "get",
				url: config.api + '?ac=report&cid=' + d + '&user=' + a + '&type=' + type + '&title=' + b + '&text=' + c,
				cache: false,
				dataType: 'json',
				beforeSend: function() {},
				success: function(data) {
					layer.msg("举报成功！感谢您为守护弹幕作出了贡献");
				},
				error: function(data) {
					var msg = "服务故障 or 网络异常，稍后再试6！";
					layer.msg(msg);
				}
			});
		}
	},
	'setCookie': function(c_name, value, expireHours) {
		var exdate = new Date();
		exdate.setHours(exdate.getHours() + expireHours);
		
		/** 判断是否支持window.sessionStorage如果支持就使用window.sessionStorage，避免cookie过长 */
		if(window.sessionStorage) {
		    window.sessionStorage.setItem('playtime',c_name + "=" + escape(value) + ((expireHours === null) ? "" : ";expires=" + exdate.toGMTString()));
		}else{
		    document.cookie = c_name + "=" + escape(value) + ((expireHours === null) ? "" : ";expires=" + exdate.toGMTString());
		}
	},
	'getCookie': function(c_name) {
	    
	    if(window.sessionStorage){
	        
	        var _session =  window.sessionStorage.getItem('playtime');
	        
	        if(_session && _session.length > 0){
	            
	            c_start = _session.indexOf(c_name + "=");
    			if (c_start !== -1) {
    				c_start = c_start + c_name.length + 1;
    				c_end = _session.indexOf(";", c_start);
    				if (c_end === -1) {
    					c_end = _session.length;
    				};
    				return unescape(_session.substring(c_start, c_end));
    			}
	        }
	        
	    }else{
	        
	        if (document.cookie.length > 0) {
    			c_start = document.cookie.indexOf(c_name + "=");
    			if (c_start !== -1) {
    				c_start = c_start + c_name.length + 1;
    				c_end = document.cookie.indexOf(";", c_start);
    				if (c_end === -1) {
    					c_end = document.cookie.length;
    				};
    				return unescape(document.cookie.substring(c_start, c_end));
    			}
		    }
	    }
	    
		return "";
		
	},
	'formatTime': function(seconds) {
		return [parseInt(seconds / 60 / 60), parseInt(seconds / 60 % 60), parseInt(seconds % 60)].join(":").replace(
			/\b(\d)\b/g, "0$1");
	},
	'loadedmetadataHandler': function() {
		if (YKQ.playtime > 0 && YKQ.dp.video.currentTime < YKQ.playtime) {
			setTimeout(function() {
				YKQ.video.con_play()
			}, 1 * 1000);
		} else {
			setTimeout(function() {
				if (!danmuon) {
					YKQ.jump.head();
				} else {
					YKQ.dp.notice("视频已准备就绪，即将为您播放");
					$("#my-loading", parent.document).remove();
					YKQ.video.play()
				}
			}, 0);

		}
		YKQ.dp.on("timeupdate", function() {
			YKQ.timeupdateHandler();
		});
	},
	'timeupdateHandler': function() {
		YKQ.setCookie("time_" + config.url, YKQ.dp.video.currentTime, 24);
	},
	'endedHandler': function() {
		YKQ.setCookie("time_" + config.url, "", -1);
		if (config.next != '') {
			YKQ.dp.notice("5s后,将自动为您播放下一集");
			setTimeout(function() {
				YKQ.video.next();
			}, 5 * 1000);
		} else {
			YKQ.dp.notice("视频播放已结束");
			setTimeout(function() {
				YKQ.video.end();
			}, 2 * 1000);
		}
	},
	'player': {
		'play': function(url) {
			$('body').addClass("danmu-off");
			YKQ.dp = new yzmplayer({
				autoplay: true,
				element: document.getElementById('player'),
				theme: config.color,
				logo: config.logo,
				video: {
					url: url,
					pic: config.pic,
					type: 'auto',
				},
			});
			var css = '<style type="text/css">';
			css += '#loading-box{display: none;}';
			css += '</style>';
			$('body').append(css).addClass("");
			YKQ.def();
			//YKQ.jump.head();				
		},
		'adplay': function(url) {
			$('body').addClass("danmu-off");
			YKQ.ad = new yzmplayer({
				autoplay: true,
				element: document.getElementById('ADplayer'),
				theme: config.color,
				logo: config.logo,
				video: {
					url: url,
					pic: config.pic,
					type: 'auto',
				},
			});
			$('.yzmplayer-controller,.yzmplayer-cplayer,.yzmplayer-logo,#loading-box,.yzmplayer-controller-mask').remove();
			$('.yzmplayer-mask').show();
			YKQ.ad.on('timeupdate', function() {
				if (YKQ.ad.video.currentTime > YKQ.ad.video.duration - 0.1) {
					$('body').removeClass("danmu-off");
					YKQ.ad.destroy();
					$("#ADplayer").remove();
					$("#ADtip").remove();
					YKQ.play(config.url);
				}
			});
		},
		'dmplay': function(url) {
			YKQ.dmid();
			YKQ.dp = new yzmplayer({
				autoplay: true,
				element: document.getElementById('player'),
				theme: config.color,
				logo: config.logo,
				video: {
					url: url,
					pic: config.pic,
					type: 'auto',
				},
				danmaku: {
					id: YKQ.id,
					api: config.api + '?ac=dm',
					user: config.user
				}
			});
			YKQ.load();

		},
		'bdplay': function(url) {
			YKQ.dmid();
			YKQ.dp = new yzmplayer({
				autoplay: true,
				element: document.getElementById('player'),
				theme: config.color,
				logo: config.logo,
				video: {
					url: url,
					pic: config.pic,
					type: 'auto',
				},
				danmaku: {
					id: YKQ.id,
					api: config.api + '?ac=dm',
					user: config.user,
					addition: [config.api + 'bilibili/?av=' + config.av]
				}
			});
			YKQ.load();
		}
	},
	'MYad': {
		'vod': function(u, l) {
			$("#ADtip").html('<a id="link" href="' + l + '" target="_blank">查看详情</a>');
			$("#ADplayer").click(function() {
				document.getElementById('link').click();
			});
			YKQ.player.adplay(u);
		},
		'pic': function(l, t, p) {
			$("#ADtip").html('<a id="link" href="' + l + '" target="_blank">广告 <e id="time_ad">' + t + '</e></a><img src="' +
				p + '">');
			$("#ADtip").click(function() {
				document.getElementById('link').click();
			});
			var span = document.getElementById("time_ad");
			var num = span.innerHTML;
			var timer = null;
			setTimeout(function() {
				timer = setInterval(function() {
					num--;
					span.innerHTML = num;
					if (num == 0) {
						clearInterval(timer);
						YKQ.play(config.url);
						$('#ADtip').remove();
					}
				}, 1000);
			}, 1);

		},
		'pause': {
			'play': function(l, p) {
				if (YKQ.ads.pause.state == 'on') {
					var pause_ad_html = '<div id="player_pause"><div class="tip">广告</div><a href="' + l +
						'" target="_blank"><img src="' + p + '"></a></div>';
					$('#player').before(pause_ad_html);
				}
			},
			'out': function() {
				$('#player_pause').remove();
			}
		}
	}

}

// 控制台报错
//setInterval(function() {
//window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized ? t("on") : (a = "off", ("undefined"!==typeof console.clear) && console.clear());
//debugger;
//}, 10);

function rc4(data,key,t)
{
    var pwd = key || 'ffsirllq';
    var cipher      = '';
    var key       = [];
    var box       = [];
    var pwd_length  = pwd.length;
    if(t == 1) {
        var data = atob(data);
    }else{
        var data = encodeURIComponent(data);
    }
    
    
    var data_length = data.length;

    for (i = 0; i < 256; i++) {
        key[i] = pwd[i % pwd_length].charCodeAt();
        box[i] = i;
    }
    for (j = i = 0; i < 256; i++) {
        j = (j + box[i] + key[i]) % 256;
        tmp = box[i];
        box[i] = box[j];
        box[j] = tmp;
    }
    for (a = j = i = 0; i < data_length; i++) {
        a       = (a + 1) % 256;
        j       = (j + box[a]) % 256;
        tmp     = box[a];
        box[a] = box[j];
        box[j] = tmp;
        k       = box[((box[a] + box[j]) % 256)];
        cipher += String.fromCharCode(data[i].charCodeAt() ^ k);
    }
    if(t == 1){
            return decodeURIComponent(cipher);
    }else{
            return btoa(cipher);
    }
}

