!function (environment) {
	/**
	* load asked file
	* @param files array of files to be loaded
	* @param callback general callback when all files are loaded
	*/
	environment['include'] = function(files, callback) {
		var doc = document, body = "body", emptyFn = function() { },
		    cache = { }, scriptCounter = 0, time = 1;

		!files.pop && (files = [files]);
		callback = callback || emptyFn;

		/**
		* create a script node with asked file
		* @param   file            the file
		* @param   fileCallback    the callback for the current script
		* @param   obj             the object loaded in file
		* @param   script          placeholder for the script element
		* @return  void
		*/
		function create(file, fileCallback, obj, script, loaded) {
			script = doc.createElement("script");
			scriptCounter++;

			script.onload = script.onreadystatechange = function(e, i) {
				i = 0, e = this.readyState || e.type;

				//seach the loaded, load or complete expression
				if (!e.search("load|complete") && !loaded) {
					obj ?
					//wait the javascript to be parsed to controll if object exists
						(file = function() {
							environment[obj] ? countFiles(fileCallback) : setTimeout(file, time);
							++i > time && (file = emptyFn);
						})() :
						countFiles(fileCallback);

					loaded = time;
				}
			};

			script.async = !0;
			script.src = file;

			doc[body].appendChild(script);
		}

		/**
		* count files loaded and launch callback
		* @param fileCallback  callback of the current file
		* @return void
		*/
		function countFiles(fileCallback) {
			fileCallback();
			!--scriptCounter && callback();
		}

		/**
		* parse sent script and load them
		* @param i             placeholder for the loops
		* @param script        placeholder for all scripts
		* @param obj           placeholder for the aksed object
		* @param callbackFile  placholder for the callback function
		* @return void
		* @return void
		*/
		!function include(i, script, obj, callbackFile) {
			if (!doc[body]) return setTimeout(include, time);

			script = doc.getElementsByTagName("script");
			callbackFile = emptyFn;

			for (i in script) script[i].src && (cache[script[i].src] = i);

			for (i = files.length; i--;)
				files[i].pop ?
					(script = files[i][0], callbackFile = files[i][1], obj = files[i][2]) :
					(script = files[i]),
				cache[script] ?
					callbackFile() :
					create(script, callbackFile, obj);

			!scriptCounter && callback();
		}();
	};
} (this);

function trace(pVal) {
	if (window.opera) {
		opera.postError(pVal);

	} else if (window.debugService) {
		if (typeof pVal === "object") {
			window.debugService.inspect("trace", pVal);

		} else {
			window.debugService.trace(pVal);
		}

	} else if (window.console) {
		if (console.dir && typeof pVal === "object") {
			console.dir(pVal);

		} else {
			console.log(pVal);
		}

	} else {
		alert(pVal);
	}
}

window.Date.prototype.f = function (format) {
	format = format + "";
	var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	    dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
	    lz = function (x) { return (x < 0 || x > 9 ? "" : "0") + x; },
	    date = this,
	    result = "",
	    iFormat = 0,
	    y = date.getYear() + "",
	    M = date.getMonth() + 1,
	    d = date.getDate(),
	    E = date.getDay(),
	    H = date.getHours(),
	    m = date.getMinutes(),
	    s = date.getSeconds(),
	    value = new Object();
	// Convert real date parts into formatted versions
	if (y.length < 4) { y = "" + (y - 0 + 1900); }
	value["y"] = "" + y;
	value["yyyy"] = y;
	value["yy"] = y.substr(2, 4);
	value["M"] = M;
	value["MM"] = lz(M);
	value["MMM"] = monthNames[M - 1];
	value["NNN"] = monthNames[M - 1].substr(0, 3);
	value["N"] = monthNames[M - 1].substr(0, 1);
	value["d"] = d;
	value["dd"] = lz(d);
	value["e"] = dayNames[E].substr(0, 1);
	value["ee"] = dayNames[E].substr(0, 2);
	value["E"] = dayNames[E].substr(0, 3);
	value["EE"] = dayNames[E];
	value["H"] = H;
	value["HH"] = lz(H);
	if (H == 0) { value["h"] = 12; }
	else if (H > 12) { value["h"] = H - 12; }
	else { value["h"] = H; }
	value["hh"] = lz(value["h"]);
	if (H > 11) { value["K"] = H - 12; } else { value["K"] = H; }
	value["k"] = H + 1;
	value["KK"] = lz(value["K"]);
	value["kk"] = lz(value["k"]);
	if (H > 11) { value["a"] = "PM"; }
	else { value["a"] = "AM"; }
	value["m"] = m;
	value["mm"] = lz(m);
	value["s"] = s;
	value["ss"] = lz(s);
	while (iFormat < format.length) {
		var c = format.charAt(iFormat), token = "";
		while ((format.charAt(iFormat) == c) && (iFormat < format.length)) {
			token += format.charAt(iFormat++);
		}
		if (value[token] != null) { result = result + value[token]; }
		else { result = result + token; }
	}
	return result;
};

(function(pWindow) {
  "use strict";
  if (!pWindow.efx) {
    pWindow.efx = { };
  }

  if (!String.isNullOrEmpty) {
    String.isNullOrEmpty = function(str) {
      if (!str) return true;
      return (str.toString()).replace( /^\s*|\s*$/g , '') === '';
    };
  }

  if (!String.format) {
    var compositeFormattingRegExp = new RegExp( /{(\d+)}|{(\d+):([^\r\n{}]+)}/g );

    String.format = function(pSource, pParams) {
      if (!(pParams instanceof Array)) pParams = Array.prototype.slice.call(arguments, 1);
      var fmtArgs, cArg, aFmt, theString, target = pSource;

      while ((fmtArgs = compositeFormattingRegExp.exec(pSource)) != null) {
        cArg = pParams[parseInt(fmtArgs[1] || fmtArgs[2], 10)];
        aFmt = fmtArgs[3] || "";

        if (isNull(cArg)) {
          theString = "";

        } else {
          if (cArg.toFormattedString) {
            theString = cArg.toFormattedString(aFmt);

          } else {
            if (cArg.format) {
              theString = cArg.format(aFmt);

            } else {
              theString = cArg.toString();
            }
          }
        }

        target = target.split(fmtArgs[0]).join(theString);
      }
      ;

      return target;
    };
  }

  //var rpcUrl = document.location.pathname.replace('index/key/','post/key/'),
var rpcUrl = "",    
      cache = { },
      hasClassRxCache = { },
      removeClassRxCache = { },
      UNDEFINED,
      loaded,
      inited,
      needsInit,
      configurationLoaded,
      configuration = { },
      langResources = { },
      mainDrawArea = function() {
        return document.getElementById("tsc_content");
      },	    
      localize = function(key) {
        return langResources[key];
      },	    
      isNull = function(pObject) {
        if (pObject === UNDEFINED) return true;
        if (pObject === null) return true;
        return false;
      },	    
      getParent = function(node, parentName) {
        ///<param name="parentName" type="String" />
        parentName = parentName.toUpperCase();
        while (node && node.parentNode) {
          node = node.parentNode;
          if (node.nodeType === 1 && node.nodeName.toUpperCase() === parentName)
            return node;
        }

        return null;
      },	    
      hasClass = function(node, className) {
        if (!hasClassRxCache[className]) {
          hasClassRxCache[className] = new RegExp('(^|\\s)' + className + '(\\s|$)');
        }

        return (hasClassRxCache[className].test(node.className));
      },	    
      addClass = function(node, className) {
        if (!hasClass(node, className))
          node.className = ((node.className || '') == '' ? '' : node.className + ' ') + className;
      },	    
      removeClass = function(node, className) {
        if (!removeClassRxCache[className]) {
          removeClassRxCache[className] = new RegExp('(^|\\s)' + className);
        }

        if (String.isNullOrEmpty(className)) return;
        node.className = node.className.replace(removeClassRxCache[className], '').replace( /^\s+|(\s)\s+/g , '$1');
      },	    
      toogleVisibility = function(node, isVisible) {
        node.style.display = isVisible ? "block" : "none";
      },
      removeNode = function(node) {
        node.parentNode.removeChild(node);
      },
      getElementSize = function(node, includeBorderAndPadding) {
        return includeBorderAndPadding ?
			//get a node's dimensions (including padding and border)
          { w: node.offsetWidth, h: node.offsetHeight } :
			//get dimensions without border but includes padding
          { w: node.clientWidth, h: node.clientHeight };
      },
      initialize = function() {
        if (!needsInit) return;
        if (inited || typeof loaded === 'undefined' || !loaded || !configurationLoaded) return;
        inited = true;
        efx.showScreen("listQuotesScreen");
      },	    
      oldResizeEvent,
      resizeHooked,
      addResizeEvent = function(method) {
        if (resizeHooked) throw new Error("Resize event already hooked");

        resizeHooked = true;
        oldResizeEvent = pWindow.onresize;

        pWindow.onresize = fixEvent(pWindow, function(e) {
          method();
          oldResizeEvent && oldResizeEvent(e);
        });
      },	    
      removeResizeEvent = function() {
        if (resizeHooked) {
          resizeHooked = false;
          pWindow.onresize = oldResizeEvent;
        }
      },
      eventNodes = "preventDefault stopPropagation type altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),
      cloneEvent = function(pEvent) {
        var o = { };
        eventNodes.forEach(function(item) {
          o[item] = pEvent[item];
        });

        o.originalEvent = pEvent;
        return o;
      },
      standardEventFix = function(node, delegate) {
        return function(event) {
          event = event || window.event;
          //make a copy of the event, so we can overwrite some props...
          var e = event.$cloned ? event : cloneEvent(event),
              target = event.target, t = event.type;
          e.$cloned = true;
          e.currentTarget = node;

          e.stop = function() {
            event.stopPropagation();
            event.preventDefault();
          };

          if (t.match( /(click|mouse|menu)/i )) {
            if (isNull(e.pageX) && !isNull(e.clientX)) {
              var doc = document.documentElement, body = document.body;
              e.pageX = e.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
              e.pageY = e.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc && doc.clientTop || body && body.clientTop || 0);
            }

            if (t.match( /DOMMouseScroll|mousewheel/ )) {
              e.wheelDelta = (event.wheelDelta) ? event.wheelDelta / 120 : -(event.detail || 0) / 3;
            }
          }

          while (target && target.nodeType == 3) target = target.parentNode;
          e.target = target;
          delegate(e, node);
        };
      },
      ieEventFix = function(node, delegate) {
        var d = standardEventFix(node, delegate);
        return function(e) {
          var event = window.event;
          // map .target to .srcElement
          event.target = event.srcElement;
          event.preventDefault = function() { event.returnValue = false; };
          event.stopPropagation = function() { event.cancelBubble = true; };
          d(event);

          // null out these properties to prevent memory leaks
          event.target = event.relatedTarget = event.preventDefault = event.stopPropagation = event = null;
        };
      },
      fixEvent = document.addEventListener ? standardEventFix : ieEventFix,
      addOnClick = function(node, delegate) {
        node.onclick = fixEvent(node, delegate);
      },
      getAttr = function(node, attr) {
        return node.getAttribute(attr) || (node.attributes[attr] ? node.attributes[attr].nodeValue : null);
      },
      getFormElementType = function(node) {
        var n = node.nodeName.toLowerCase();
        switch (n) {
        case "textarea":
          return n;
        case "select":
          return String.isNullOrEmpty(getAttr(node, "multiple")) ? n : "select-multiple";
        case "input":
          return getAttr(node, "type");
        }

        return n;
      },
      getValue = function(node, asArray) {
        var ops = [], si, idx = 0, value, n, g = [], nt = getFormElementType(node);

        n = node.name || "";
        if (n !== "") g = getArguments(document.getElementsByName(n));

        switch (nt) {
        case 'select':
          si = node.options[node.selectedIndex];
          value = si.value || si.text;
          break;
        case 'select-multiple':
          value = [];
          while ((si = node.selectedIndex) !== -1) {
            (ops[ops.length] = node.options[si]).selected = false;
            value[value.length] = si;
          }

          ops.forEach(function(option) { option.selected = true; });
          return value;
        case 'checkbox':
          value = [];

          g.forEach(function(option) {
            if (option.checked || option.selected) {
              ops[ops.length] = option;
              value[value.length] = option.value;
            }
          }
          );

          return value;
        case 'radio':
          for (var l = g.length; idx < l; ++idx) {
            si = g[idx++];
            if (si.checked || si.selected) {
              value = si.value;
              break;
            }
          }
          ;
          break;
        default:
          value = node.value;
        }

        return asArray ? [value] : value;
      },	    
      drawLoading = function(parentUl) {
        var b = document.createElement("b");
        b.className = "loading";
        b.appendChild(document.createTextNode("Loading..."));
        parentUl.appendChild(b);
        return b;
      },	    
      createTreeNode = function(oldNode) {
        return {
          "ID": oldNode.ID,
          "ContentType": oldNode.ContentType,
          "Selected": oldNode.Selected,
          "Name": oldNode.Name,
          "LastModificationDate": oldNode.LastModificationDate
        };
      },	    
      isOptionEnabled = function(option) {
        if (!option) return 0;
        if (option.Selected) return 2;

        var children = option.Children, keys = children && Object.keys(children);
        if (!keys) return 0;
        for (var i = 0; i < keys.length; ++i) {
          if (isOptionEnabled(children[keys[i]]) != 0) return 1;
        }

        return 0;
      },	    
      dictionaryToTree = function(dictionary) {
        if (!dictionary || !dictionary.ID) return null;

        var children = dictionary.Children,
            newChildren = [],
            node = createTreeNode(dictionary);

        if (dictionary.Selected) return node;

        if (isOptionEnabled(dictionary) == 1 && children) {
          Object.keys(children).forEach(function(key) {
            var val = children[key];
            var entry = dictionaryToTree(val);
            val && entry && (newChildren[newChildren.length] = entry);
          });

          newChildren.length && (node.Children = newChildren);
          return node;
        }

        return null;
      },	    
      treeToDictionary = function(tree) {
        if (!tree || !tree.ID) return null;
        var i, item,
            children = tree.Children,
            newChildren = { },
            node = createTreeNode(tree);

        if (children) {
          for (i = 0; i < children.length; ++i) {
            item = children[i];
            newChildren[item.ID] = treeToDictionary(item);
          }

          i && (node.Children = newChildren);
        }

        return node;
      },	    
      createEmptyTreeNode = function(id, type, name) {
        return {
          "ID": id,
          "ContentType": type || "Other",
          "Selected": false,
          "Name": name || "",
          "Children": { },
          "LastModificationDate": new Date()
        };
      },	    
      HtmlOptions = function(drawInto, optionsID, initialOptionsState, sourceLanguage) {
        initialOptionsState = initialOptionsState || createEmptyTreeNode(optionsID);
        var loading = drawLoading(drawInto),
            optionsName = "tsc_optionsId_" + optionsID,
            e = new RPC(rpcUrl,
              function(data) {
                data = JSON.parse(data);
                removeNode(loading);
                //ID, Name, ContentType: Folder/Content/Mixed/File
                var ul = document.createElement("ul");
                ul.setAttribute("id", optionsName);
                addClass(ul, "tsc_options");

                data.forEach(function(nodeItem) {
                  var li = document.createElement("li"),
                      label = document.createElement("label"),
                      span = document.createElement("span"),
                      checkbox = document.createElement("input"),
                      ios = initialOptionsState.Children[nodeItem.ID];

                  //cbID = optionsID + nodeItem.ID

                  label.setAttribute("unselectable", "on");
                  checkbox.setAttribute("type", "checkbox");
                  checkbox.setAttribute("name", "tsc_options_" + optionsID);
                  checkbox.setAttribute("value", nodeItem.ID);
                  checkbox.checked = ios && ios.Selected;

                  label.appendChild(checkbox);
                  label.appendChild(span);
                  span.appendChild(document.createTextNode(nodeItem.Name));
                  li.appendChild(label);
                  ul.appendChild(li);
                });

                drawInto.appendChild(ul);
              },
              function(errorMessage) {
                createMessage(String.format("Failed to retrieve options. {0}", errorMessage), "error");
              }
            );

        e.execute("GetChildren", optionsID, sourceLanguage, optionsID);

        this.getSelected = function() {
          var tree = document.getElementById(optionsName);
          if (tree) {
            var inputs = getArguments(tree.getElementsByTagName("input"));
            if (inputs && inputs.length) {
              inputs.forEach(function(input) {
                var id = getAttr(input, "value");
                if (input.checked) {
                  var t = (initialOptionsState.Children[id] || (initialOptionsState.Children[id] = createEmptyTreeNode(id)));
                  t.Selected = true;
                  t.LastModificationDate = new Date();

                } else {
                  initialOptionsState.Children[id] = null;
                }
              });
            }

            return dictionaryToTree(initialOptionsState);
          }

          return false;
        };
      },
      HtmlTree = function(drawInto, treeID, initialTreeState, sourceLanguage) {
        initialTreeState = initialTreeState || createEmptyTreeNode(treeID);

        var parents = { },
            setCheckboxState = function(node, state) {
              var startNode = findStartNode(node.value);
              startNode.LastModificationDate = new Date();

              if (node.nextSibling) {
                node.nextSibling.className = 'tsc_checkstate' + state; 
              }              

              switch (state) {
              case 0:
//not checked
                node.removeAttribute("disabled");
                startNode.Selected = node.checked = false;
                return;
              case 1:
//grayed out
                node.setAttribute("disabled", "disabled");
                startNode.Selected = !(node.checked = true);
                return;
              case 2:
//checked
                node.removeAttribute("disabled");
                startNode.Selected = node.checked = true;
                return;
              }
            },
            treeSetCheckboxState = function(node, state) {
              setCheckboxState(getParent(node, "li").getElementsByTagName("input")[0], state);
            },
            treeCheckboxClick = function(event, node) {
              event.stop();

              node = document.getElementById(getAttr(node, "for")); //node has the "input" to be checked/unchecked

              var startNode = findStartNode(node.value);
              var isEnabled = String.isNullOrEmpty(getAttr(node, "disabled"));
              if (isEnabled) {
                setCheckboxState(node, node.checked ? 0 : 2);
                //startNode.Selected = node.checked = !node.checked;
                //startNode.LastModificationDate = new Date();
              }

              recursiveCheckDown(node, node.checked);
              recursiveWalkDown(startNode, node.checked);
              recursiveCheckUp(node);
            },	    	    
            findStartNode = function(nodeName) {
              var parent, nodes = [], current = initialTreeState;
              while (parent = parents[nodeName]) {
                nodes[nodes.length] = nodeName.split("|")[1];
                nodeName = parent;
              }
              nodes.reverse();
              for (var i = 0; i < nodes.length; ++i) {
                current = current.Children[nodes[i]];
              }

              return current;
            },	    	    
            recursiveWalkDown = function(startNode, checked) {
              startNode.Selected = checked;
              startNode.LastModificationDate = new Date();
              var children = startNode.Children, keys = children && Object.keys(children);
              if (!(keys && keys.length)) return;

              keys.forEach(function(key) {
                recursiveWalkDown(children[key], checked);
              });
            },
            recursiveCheckUp = function(node) {
              var parentUl = getParent(node, "ul");
              if (parentUl.length == 0) return;

              var currentCheckboxes = treeGetChildrenInputs(parentUl),
                  checked = 0;

              currentCheckboxes.forEach(function(input) {
                if (input.checked) ++checked;
              });

              parentUl = getParent(parentUl, "li");

              if (parentUl == null) return;
              parentUl = parentUl.getElementsByTagName("input");
              if (parentUl.length === 0) return;

              node = parentUl[0];

              if (checked === currentCheckboxes.length) {
                treeSetCheckboxState(node, 2);

              } else if (checked > 0) {
                treeSetCheckboxState(node, 1);

              } else {
                treeSetCheckboxState(node, 0);
              }

              recursiveCheckUp(node);
            },
            recursiveCheckDown = function(node, checked) {
              node.removeAttribute("disabled");
              if (checked) {
                treeSetCheckboxState(node, 2);
              } else {
                treeSetCheckboxState(node, 0);
              }
              var nextUl = treeGetNextUl(node);
              if (nextUl == null) return;

              treeGetChildrenInputs(nextUl).forEach(function(input) {
                var type = input.value.split("|")[0];
                input.checked = checked;
                if (checked) {
                  treeSetCheckboxState(input, 2);
                } else {
                  treeSetCheckboxState(input, 0);
                }
                if (type === "Mixed" || type === "Folder") {
                  recursiveCheckDown(input, checked);
                }
              });
            },
        treeGetNextUl = function(node) {
              node = getParent(node, "li").childNodes;
              var l = node.length, cn;
              while (l > 0) {
                cn = node[--l];
                if (cn.nodeName.toUpperCase() === "UL") return cn;
              }

              return null;
            },
            treeGetChildrenInputs = function(node) {
              var ret = [];
              getArguments(node.children).forEach(function(liChild) {
                if (liChild.nodeName.toUpperCase() !== "LI") return;

                getArguments(liChild.getElementsByTagName("input")).forEach(function(input) {
                  if (input.parentNode != liChild) return;
                  ret.push(input);
                });
              });

              return ret;
            },
            treeToogleBranch = function(event, node) {
              event.stop();

              var parts = node.href.split("|"),
                  parentLi = getParent(node, "li"),
                  openClass = "open";

              if (hasClass(node, openClass)) {
                removeClass(node, openClass);
                toogleBranch(parentLi, false);

              } else {
                addClass(node, openClass);
                if (hasClass(parentLi, "loaded")) {
                  toogleBranch(parentLi, true);

                } else {
                  drawBranch(parentLi, parts[1], parts[2], null, node.__enabledOptions, node.__parents);
                }
              }
            },
            toogleBranch = function(node, isBranchVisible) {
              ///<param name="parentNode" type="HTMLElement" />
              ///<param name="isBranchVisible" type="Boolean" />
              toogleVisibility(node.getElementsByTagName("ul")[0], isBranchVisible);
            },
            drawBranch = function(parentNode, parentNodeID, treeName, branchClass, enabledOptions, parentNodeDescription) {
              var loading = drawLoading(parentNode),
                  e = new RPC(rpcUrl,
                    function(data) {
                      removeNode(loading);
                      data = JSON.parse(data);
                      //ID, Name, ContentType: Folder/Content/Mixed/File
                      var ul = document.createElement("ul"),
                          checked = parentNode.getElementsByTagName("input");

                      checked = checked.length ? (checked[0].checked && String.isNullOrEmpty(getAttr(checked[0], "disabled"))) : false;
                      if (!String.isNullOrEmpty(branchClass)) {
                        ul.className = branchClass;
                        ul.setAttribute("id", "tsc_treeId_" + treeName);
                      }

                      addClass(parentNode, "loaded");
                      data.forEach(function(nodeItem) {
                        var li = document.createElement("li"),
                            div = document.createElement("div"),
                            label = document.createElement("label"),
                            checkbox = document.createElement("input"),
                            cbID = treeName + nodeItem.ContentType + nodeItem.ID,
                            nodeInOptions = (enabledOptions[nodeItem.ID] || (enabledOptions[nodeItem.ID] = createEmptyTreeNode(nodeItem.ID, nodeItem.ContentType, nodeItem.Name))),
                            value = nodeItem.ContentType + "|" + nodeItem.ID;

                        li.className = "tsc_" + nodeItem.ContentType;
                        //li.setAttribute("unselectable", "on");
                        label.setAttribute("unselectable", "on");
                        label.setAttribute("for", cbID);                        

                        checkbox.setAttribute("type", "checkbox");
                        checkbox.setAttribute("name", "tsc_tree_" + treeName);

                        parents[value] = parentNodeDescription;

                        checkbox.setAttribute("value", value);
                        checkbox.setAttribute("id", cbID);

                        if (checked) {
                          setCheckboxState(checkbox, 2);
                          div.className = 'tsc_checkstate2';
                          //checkbox.setAttribute("checked", "checked");

                        } else {
                          setCheckboxState(checkbox, isOptionEnabled(nodeInOptions));
                          div.className = 'tsc_checkstate' + isOptionEnabled(nodeInOptions);
                        }

                        addOnClick(label, treeCheckboxClick);

                        if (nodeItem.ContentType === "Folder" || nodeItem.ContentType === "Mixed") {
                          var link = document.createElement("a");
                          link.__enabledOptions = nodeInOptions.Children || (nodeInOptions.Children = { });
                          link.__parents = value;
                          link.setAttribute("href", "#expand|" + nodeItem.ID + "|" + treeName);
                          
                          // Expand all the tree
	    		    			      var tsc_enabled_search = document.getElementById("tsc_expand_tree");
	    		    			      if(tsc_enabled_search.className !== "collapse") { 
	    		    			          var openClass = "open";
	    		    			          drawBranch(li, nodeItem.ID, treeName, null, nodeInOptions.Children || (nodeInOptions.Children = { }), value);
	    		    			          addClass(link, openClass);
	    		    			      }

                          addOnClick(link, treeToogleBranch);
                          link.appendChild(document.createTextNode("+"));
                          li.appendChild(link);
                          li.appendChild(document.createTextNode(" "));
                        }

                        label.appendChild(document.createTextNode(nodeItem.Name));
                        li.appendChild(checkbox);
                        li.appendChild(div);
                        li.appendChild(label);
                        ul.appendChild(li);
                      });

                      parentNode.appendChild(ul);
                    },
                    function(errorMessage) {
                      createMessage(String.format("Failed to retrieve tree branch. {0}", errorMessage), "error");
                    }
                  );
              e.execute("GetChildren", parentNodeID, sourceLanguage, treeName);
            };

        drawBranch(drawInto, "0", treeID, "tsc_tree", initialTreeState.Children, "0");
        this.getSelected = function() {
          return dictionaryToTree(initialTreeState);
        };
      },
      ResizablePanes = function(container, maxColumns, minColWidth, margin, usePercentaje) {
        minColWidth = minColWidth || 320;
        margin = margin || 18; //0.83333

        var collapseCols = 0,
            paneCount = 0, panes = container.getElementsByTagName("dl"),
            theItems = [], index, pane,
            oldWidth,
            calcMargin = margin + (usePercentaje ? "%" : "px");

        for (index = 0; index < panes.length; ++index) {
          pane = panes[index];
          if (pane.parentNode == container && hasClass(pane, "tsc_panel")) {
            pane.setAttribute("id", "tsc_pane" + paneCount);
            theItems[paneCount] = pane;
            ++paneCount;
          }
          ;
        }

        if (maxColumns > paneCount) maxColumns = paneCount;

        for (index = 0; index < paneCount; ++index) {
          theItems[theItems.length] = document.getElementById('tsc_pane' + index);
        }
        ;

        this.draw = function() {
          var w = getElementSize(container).w,
              width = w,
              columns = maxColumns,
              e,
              columnID;

          if (oldWidth == w) return;
          oldWidth = w;

          if (!usePercentaje) {
            w -= margin;
          }

          if (w > minColWidth) {
            for (index = maxColumns - 1; index >= 0; --index) {
              width = w / (index + 1);
              if (width < minColWidth) continue;
              columns = index + 1;
              break;
            }
          } else {
            columns = 1;
          }

          if (usePercentaje) {
            width = ((100 - margin) / columns - margin) + "%";

          } else {
            width = ((width - margin) | 0) + "px";
          }

          var theColumns = [], // html elements
              theColumnHeights = [];

          // not our first time through so panes have already been moved to column divs
          if (collapseCols > 0) {
            // move panes back into the pane container
            for (index = 0; index < paneCount; ++index) {
              theItems[index].parentNode.removeChild(theItems[index]); // iPad null
            }
            ;

            // remove existing columns if needed
            for (index = 0; index < collapseCols; ++index) {
              e = document.getElementById('column-' + index);
              e.parentNode.removeChild(e); // iPad null
            }
            ;
          }
          ;

          collapseCols = columns;
          // add columns if needed
          for (index = 0; index < collapseCols; ++index) {
            var column = document.createElement('div');
            column.id = 'column-' + index;
            addClass(column, "tsc_multi_column");
            column.style.width = width;
            column.style.marginLeft = calcMargin; //column.style.marginRight = margin+(usePercentaje ? "%":"px");
            container.appendChild(column);
            columnID = theColumns.length;
            theColumns[columnID] = column;
            theColumnHeights[columnID] = column.offsetHeight;
          }
          ;


          for (index = 0; index < paneCount; ++index) {
            e = theItems[index]; //document.getElementById('tsc_pane' + m);

            // place the first (number of columns) panes in order
            if (index < theColumns.length) {
              columnID = index;

            } else {
              // then just fill which ever is the shortest column with the rest
              var minHeight = 0;
              for (var n = 0; n < theColumnHeights.length; n++) {
                if (n == 0 || theColumnHeights[n] < minHeight) {
                  minHeight = theColumnHeights[n];
                  columnID = n;
                }
              }
            }
            ;

            if (theColumns[columnID]) {
              // pane order is preserved reading left-right, top-down
              e.parentNode && e.parentNode.removeChild(e); // iPad null
              theColumns[columnID].appendChild(e);
              theColumnHeights[columnID] = theColumns[columnID].offsetHeight;
            }
            ;
          }
          ;
        };
      },	    
      drawTemplate = function(templateToDraw, args) {
        removeResizeEvent();
        mainDrawArea().innerHTML = template(templateToDraw)(args || { });
      },	    
      template = function tmpl(str, data) {
        var fn = ! /\W/ .test(str) ? cache[str] = cache[str] || tmpl(document.getElementById(str).innerHTML) : new Function("obj",
          "var p=[]; with(obj){p.push('" +
            str.replace( /[\r\t\n]/g , " ")
              .replace( /'(?=[^{]*}})/g , "\t")
              .split("'").join("\\'")
              .split("\t").join("'")
              .replace( /{{=(.+?)}}/g , "',$1,'")
              .split("{{").join("');")
              .split("}}").join("p.push('")
                + "');}return p.join('');");
        return data ? fn(data) : fn;
      },
      initRequest = function() {
        if (pWindow.XMLHttpRequest) {
          return new pWindow.XMLHttpRequest();

        } else if (window.ActiveXObject) {
          try {
            return new ActiveXObject("Msxml2.XMLHTTP");
          } catch(e) {
            try {
              return new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
            }
          }
        }

        return false;
      },	    
      itoh = '0123456789ABCDEF',
      uuid = function() {
        var s = [], i;
        for (i = 0; i < 36; i++) {
          s[i] = Math.floor(Math.random() * 0x10);
        }

        s[14] = 4;
        s[19] = (s[19] & 0x3) | 0x8;
        for (i = 0; i < 36; i++) {
          s[i] = itoh[s[i]];
        }
        s[8] = s[13] = s[18] = s[23] = '-';
        return s.join('');
      },
      isoDateReviver = function(key, value) {
        if (typeof value === 'string') {
          var a = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)(?:([\+-])(\d{2})\:(\d{2}))?Z?$/ .exec(value);
          if (a) {
            return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4], +a[5], +a[6]));
          }
        }
        return value;
      },	    
      getArguments = function(pParams, pIdx) {
        ///<param name="pParams" type="Array">The parameter argument list</param>
        ///<param name="pIdx" type="Number">Index of the first argument</param>
        ///<returns type="Array" />
        pIdx = pIdx || 0;
        var ret = [];
        for(var i= pIdx; i < pParams.length; i++) {
          ret.push(pParams[i]);
        }
        return ret;
        // IE8 does not allow Array.prototype.slice.call on NodeList
        //return [].slice.call(pParams, pIdx);
      },	    
      createMessage = function(pMessage, pType) {
        document.getElementById("tsc_messages").innerHTML = "<div class='message " + (pType || "info") + "'>" + pMessage + "</div>";
      },	    
      createFormMessage = function(pMessage, pErrors) {
        var m = "";
        pErrors.forEach(function(message) {
          m += "<li>" + message + "</li>";
        });

        document.getElementById("tsc_messages").innerHTML = "<div class='message validation'><h4>" + pMessage + "</h4><ul>" + m + "</ul></div>";
      },	    
      clearMessage = function() {
        document.getElementById("tsc_messages").innerHTML = "";
      },	    
      numberOfWaitings = 0,
      showWaiting = function(pMessage) {
        if (numberOfWaitings == 0) {
          var w = document.getElementById("tsc_working");
          w.innerHTML = "<strong>" + (pMessage || "Please Wait...") + "</strong>";
          toogleVisibility(w, true);
        }

        ++numberOfWaitings;
      },	    
      hideWaiting = function() {
        --numberOfWaitings;
        if (numberOfWaitings == 0) toogleVisibility(document.getElementById("tsc_working"), false);
      },
      RPC = function(url, onSuccess, onFailure, onComplete) {
        url = url + (url.indexOf("?") === -1 ? "?" : "&") + "format=json&isAjax=true&form_key="+FORM_KEY;

        var r, running, id, tries = 0,
            executeSuccess = function(obj) {
              if (!!onSuccess) onSuccess(obj);
            },	    	    
            executeFailure = function(error) {
              if (!!onFailure) onFailure(error);
            },	    	    
            executeComplete = function() {
              if (!!onComplete) onComplete();
            },	    	    
            doRequest = function(data) {
              r = initRequest();
              id = uuid();
              running = true;

              r.onreadystatechange = function() {
                if (r.readyState !== 4 || !running) {
                  return;
                }

                running = false;

                var s;
                try {
                  s = r.status;
                } catch(e) {
                  s = 200;
                }

                if (s >= 200 && s <= 300) {
                  var msg = r.responseText, ok = true;
                  if (msg.indexOf('{') === 0) {
                    try {
                      msg = JSON.parse(msg, isoDateReviver);

                    } catch(e) {
                      ok = false;
                      executeFailure("Exception " + e + ". Data: " + msg);
                    }

                    if (ok) {
                      if (msg.error) {
                        executeFailure(msg.error);

                      } else {
                        executeSuccess(msg.result);
                      } 
                        
                      /* TODO FIX Request response id missmatch
                      else if (msg.id === id) {
                        executeSuccess(msg.result);

                      } else {
                        executeFailure(String.format("Invalid response. Sent ID doen't matches received ID. Data: {0}", msg));
                      }*/
                    }
                  }

                } else {
                  if (++tries < 3) {
                    delete r.onreadystatechange;
                    r.onreadystatechange = null;
                    doRequest(data);
                    return;
                  }

                  executeFailure(r.statusText + ":" + r.responseText);
                }

                delete r.onreadystatechange;
                r.onreadystatechange = null;
                executeComplete();
              };

              //r.open('POST', url + "&id=" + id, true);
              r.open('POST', url, true);
              r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
//              r.setRequestHeader("Content-length", data.length);

//	    		if (configuration) {
//	    			r.setRequestHeader('Authorization', 'WSSE profile="UsernameToken"');
//	    			var nonce = b64Sha1(uuid());
//					var created = new Date().toISOString();
//					var passwordDigest = b64Sha1(nonce + created + configuration.AuthorizationPassword);
//	    			r.setRequestHeader('X-Wsse',
//	    				'UsernameToken Username="' + configuration.AuthorizationUsername +
//		    			'", PasswordDigest="' + passwordDigest +
//			    		'", Created="' + created +
//				    	'", Nonce="' + nonce + '"'
//					);
//	    		}

              if (r.overrideMimeType) {
                //r.setRequestHeader("Connection", "close");
                r.overrideMimeType('application/json');
              }

              r.send(data);
            };

        this.execute = function(pMethod) {
           var id = uuid();
          doRequest(JSON.stringify({ "jsonrpc": "2.0", "method": pMethod, "params": getArguments(arguments, 1), "id": id }));
        };
      },
      validate = function(formElements) {
        var errors = [];
        if (!Array.isArray(formElements)) formElements = [formElements];

        formElements.forEach(function(validateEntry) {
          if (!validateEntry.args) validateEntry.args = [];
          if (!validateEntry.validator.apply(null, validateEntry.args)) {
            errors.push(validateEntry.message);
          }
        });

        return errors;
      },	    
      requiredValidator = function(value) {
        return value.length > 0;
      },	    
      minLengthValidator = function(value, minValue) {
        return value.length >= minValue;
      },	    
      doesNotContainValidator = function(value, values) {
        return values.indexOf(value) === -1;
      },	    
      urlValidator = function(value) {
        return /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/i .test(value);
      },	    
      packages = [], quotes = [], projects = [], trees,	    
      getPackage = function(quoteId) {
        var retQuote;
        packages.forEach(function(item) {
          if (item.ID === quoteId) {
            retQuote = item;
          }
        });

        return retQuote;
      },	    
      showQuotes = function() {
        drawTemplate("main_tpl", { "packages": packages, "quotes": quotes, "projects": projects });
      },	    
      lastTimeout;

  efx.showScreen = function(screenName) {
    if (lastTimeout) clearTimeout(lastTimeout);
    lastTimeout = null;
    efx[screenName].apply(null, getArguments(arguments, 1));
  };

  efx.doConfirm = function(title, func) {
    if (window.confirm(title.split("\\n").join("\n")))
      func.apply(null, getArguments(arguments, 2));

    return false;
  };

  efx.listQuotesScreen = function() {
    new RPC(rpcUrl,
      function(data) {
        data = JSON.parse(data);
        
        clearMessage();
        packages = [];
        quotes = [];
        projects = [];
        
        data.forEach(function(obj) {
          var status = obj.Status;
          obj.HumanStatus = status.replace( /([A-Z])/g , " $1");

          if (status == "QuoteOpen") {
            packages.push(obj);

          } else if (status.indexOf("Project") === -1) {
            quotes.push(obj);

          } else {
            projects.push(obj);
          }
        });

        showQuotes();
      },	    		
      function(errorMessage) {
        createMessage(String.format("Failed to retrieve remote data. {0}", errorMessage), "error");
      },			
      function() {
        lastTimeout = setTimeout(function() { efx.showScreen("listQuotesScreen"); }, 20000);
      }
    ).execute("ListQuotes");

    showQuotes();

    var stats = document.getElementById("tsc_status"),
        hideStats = function() {
          toogleVisibility(stats, false);
          stats.innerHTML = "";
        };

    new RPC(rpcUrl,
      function(data) {
        data = JSON.parse(data);
        
        if (data && data.length) {
          toogleVisibility(stats, true);
          stats.innerHTML = template("status_tpl")({ "statuses": data });

        } else {
          hideStats();
        }
      },			
      hideStats
    ).execute("GetStatuses");
  };

  efx.deletePackage = function(packageID) {
    new RPC(rpcUrl,
      null,
      function(errorMessage) {
        createMessage(String.format("Failed to delete package. {0}", errorMessage), "error");
      }
    ).execute("DeleteQuote", packageID, configuration.UserName);

    packages.forEach(function(item, index, arr) {
      if (item.ID === packageID) {
        arr.splice(index, 1);
      }
    });

    showQuotes();
  };

  efx.createQuoteScreen = function(isQuickQuote) {
    drawTemplate("createQuote_tpl", { "sourceLanguages": configuration.SourceLanguages, "targetLanguages": configuration.TargetLanguages, "quickQuote": isQuickQuote });
  };

  efx.showConfigurationScreen = function(fromConfig) {
    drawTemplate("config_tpl", { "fromConfig": fromConfig, "configuration": configuration });
  };

  efx.saveConfiguration = function() {
    clearMessage();
    var authToken = getValue(document.getElementById("tsc_tokenID")),
        endpoint = getValue(document.getElementById("tsc_Endpoint")),		    
        e = new RPC(rpcUrl,
          function() {
            configuration.AuthorizationToken = authToken;
            configuration.TscServerEndPoint = endpoint;
            inited = false;
            efx.init();
          },		    	
          function(errorMessage) {
            createMessage(String.format("Can not save configuration data. {0}", errorMessage), "error");
          },			
          function() {
            hideWaiting();
          }
        ),			
        errors = validate([
            { "validator": requiredValidator, "args": [authToken], "message": "The Authorization Token is required." },
            { "validator": requiredValidator, "args": [endpoint], "message": "The EndPoint URL is required." },
            { "validator": urlValidator, "args": [endpoint], "message": "The EndPoint URL is not in a valid form." },				
          ]);

    if (errors.length > 0) {
      createFormMessage("There were some errors while checking the form:", errors);
      return;
    }

    showWaiting();
    e.execute(
      "SaveConfiguration",
      authToken,
      endpoint
    );
  };

  efx.createQuote = function(quickQuote) {
    clearMessage();
    var e = new RPC(rpcUrl,
      function(quote) {
        quote = JSON.parse(quote);
        packages.push(quote);
        efx.addDocuments(quote.ID, quickQuote);
      },			
      function(errorMessage) {
        createMessage(String.format("Failed to create quote. {0}", errorMessage), "error");
      },			
      function() {
        hideWaiting();
      }
    ),
        name = getValue(document.getElementById("tsc_Name")),
        sourceLang = getValue(document.getElementById("tsc_SourceLang")),
        targetLangs = getValue(document.getElementById("tsc_TargetLangs"), true),
        errors = validate([
            { "validator": requiredValidator, "args": [name], "message": quickQuote ? "The name of the quote is required." : "The name of the package is required." },
            { "validator": minLengthValidator, "args": [targetLangs, 1], message: "You have to select at least one target language" },
					//this should never happen. Needs to be validated on the plugin. Just in case...
            { "validator": doesNotContainValidator, "args": [sourceLang, targetLangs], message: "You can not select as target language the selected source language" },
          ]);

    if (errors.length > 0) {
      createFormMessage("There were some errors while checking the form:", errors);
      return;
    }

    showWaiting();
    e.execute(
      "CreateQuote",
      name,
      getValue(document.getElementById("tsc_Notes")),
      sourceLang,
      targetLangs,
      configuration.UserName
    );
  };

  efx.requestAQuote = function(packageID, quickQuote) {
    var e = new RPC(rpcUrl,
      function() {
        packages.forEach(function(item, index, arr) {
          if (item.ID === packageID) {
            item.HumanStatus = "Quote In Progress";
            item.Status = "QuoteInProgress";
            item.Name = String.format("Creating quote for: {0}", item.Name);
            quotes.push(item);
            arr.splice(index, 1);
          }
        });

        if (quickQuote) {
          efx.listQuotesScreen();

        } else {
          showQuotes();
        }

      },	    		
      function(errorMessage) {
        createMessage(String.format("Failed to request a quote. {0}", errorMessage), "error");
      },			
      function() {
        hideWaiting();
      }
    );
	
	showWaiting();
    e.execute("SendQuoteToGpms", packageID, configuration.UserName, quickQuote || false);
  };

  efx.addDocuments = function(quoteId, quickQuote) {
    showWaiting();
    new RPC(rpcUrl,
      function(treeNodes) {
        treeNodes = JSON.parse(treeNodes) || { };
        trees = { };
        var quote = getPackage(quoteId), sourceLang = quote.SourceLanguageISOCode;

        drawTemplate("add_documents_tpl", { "quote": quote, "quickQuote": quickQuote });

        var panes = configuration.DocumentBrowserPanes,
            container = document.getElementById("tsc_columns"),
            nodesByID = { }, i;

        for (i = treeNodes.length - 1; i >= 0; --i) {
          var tn = treeNodes[i];
          nodesByID[tn.ID] = treeToDictionary(tn);
        }

        panes.forEach(function(pane) {
          var dl = document.createElement("dl"),
              dt = document.createElement("dt"),
              dd = document.createElement("dd"),
              paneID = pane.ID;
          dl.className = "tsc_panel";
          dt.appendChild(document.createTextNode(pane.Name));
		  
		  var span = document.createElement('span');
		  span.style.cssFloat = 'right'
		  span.style.marginTop = '3px';
		  span.style.fontSize = '75%';
		  
		  var anchor = document.createElement('a');
		  anchor.appendChild(document.createTextNode('Check All'));
		  span.appendChild(anchor);
		  
		  span.appendChild(document.createTextNode(' '));
		  
		  anchor = document.createElement('a');
		  anchor.appendChild(document.createTextNode('Uncheck All'));
		  span.appendChild(anchor);
		  
		  dt.appendChild(span);
		  if (pane.IncludeCheckUncheckAll) {
			console.log('includeCheckUncheckAll');
		  } else {
			console.log('do NOT includeCheckUncheckAll');
		  }
          dl.appendChild(dt);
          dl.appendChild(dd);
          container.appendChild(dl);
          trees[paneID] = (paneID.indexOf("options_") == 0) ?
            new HtmlOptions(dd, paneID, nodesByID[paneID], sourceLang) :
            new HtmlTree(dd, paneID, nodesByID[paneID], sourceLang);
        });

        var rp = new ResizablePanes(container, 100, 320, 0.833333, true);
        rp.draw();

        addResizeEvent(rp.draw);
      },	    		
      function(errorMessage) {
        createMessage(String.format("Failed to retrieve selected package items. {0}", errorMessage), "error");
      },			
      function() {
        hideWaiting();
      }
    ).execute("ListPackageFiles", quoteId);
  };

  efx.addSelectedDocuments = function(quoteId, quickQuote) {
    showWaiting();
    var data = [];
    Object.keys(trees).forEach(function(key) {
      var selected = trees[key].getSelected();
      if (selected) data.push(selected);
    });

    new RPC(rpcUrl,
      function() {
        if (quickQuote) {
          efx.requestAQuote(quoteId, true);

        } else {
          efx.listQuotesScreen();
        }
      },	    		
      function(errorMessage) {
        createMessage(String.format("Failed to export selected documents. {0}", errorMessage), "error");
      },			
      function() {
        hideWaiting();
      }
    ).execute("ModifyPackageFiles", quoteId, configuration.UserName, data);
  };

  efx.importProject = function(projectId) {
    showWaiting();
    new RPC(rpcUrl,
      function() {
        efx.listQuotesScreen();
      },	    		
      function(errorMessage) {
        createMessage(String.format("Failed to import translations. {0}", errorMessage), "error");
      },			
      function() {
        hideWaiting();
      }
    ).execute("Import", projectId, configuration.UserName);
  };

  efx.closeProject = function(projectId) {
    new RPC(rpcUrl,
      function() {
        projects.forEach(function(item, index, arr) {
          if (item.ID === projectId) {
            arr.splice(index, 1);
          }
        });

        showQuotes();
      },	    		
      function(errorMessage) {
        createMessage(String.format("Failed to close project. {0}", errorMessage), "error");
      }
    ).execute("CloseQuote", projectId, configuration.UserName);
  };

  efx.addLangResource = function(obj) {
    langResources = obj;
  };

  efx.init = function() {
    needsInit = true;
    initialize();
  }; 

  efx.startup = function(webServiceUrl) {
      rpcUrl = webServiceUrl;
      var includes = [], 
          onloadCallback = function() {
            new RPC(rpcUrl, function(data) {
              configuration = JSON.parse(data);
              configurationLoaded = true;
              initialize();          
            }, function(errorMessage) {
              createMessage(String.format("Failed to retrieve configuration. {0}", errorMessage), "error");

            }).execute("GetConfiguration");

            if (navigator)


            loaded = true;
            initialize();

    //        var oldOnload = pWindow.onload;
    //        pWindow.onload = function(e) {
    //          loaded = true;
    //          initialize();
    //          
    //          if (oldOnload) {
    //            oldOnload(e);
    //          }
    //        };
          };

    //  if (typeof JSON === 'undefined' || typeof JSON.stringify !== 'function' || typeof JSON.parse !== 'function') {
    //    includes.push('/<%=WebResource("TSC.WebClient.js.JSON.min.js")%>');
    //  }

      if ((typeof Array.isArray === 'undefined') || (typeof Array.prototype.forEach === 'undefined') || 
          (typeof Object.keys === 'undefined') || (typeof Date.prototype.toISOString === 'undefined') || 
          (typeof Date.prototype.toJSON === 'undefined') || (typeof String.prototype.trim === 'undefined')) {
        includes.push('/<%=WebResource("TSC.WebClient.js.EcmaScript5.min.js")%>');
      }  

      if (includes.length) {
        pWindow.include(includes, onloadCallback);

      } else {
        onloadCallback();
      }
  }
})(window);
