/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/player.js":
/*!********************************!*\
  !*** ./resources/js/player.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("window.onload = function onload() {\n  var playButtons = document.getElementsByClassName(\"play_btn\");\n  var audios = document.getElementsByClassName(\"audio\");\n\n  var _loop = function _loop(i) {\n    playButtons[i].addEventListener('click', function () {\n      if (audios[i].paused) {\n        for (var j = 0; j < playButtons.length; j++) {\n          if (!audios[j].paused) {\n            audios[j].pause();\n            playButtons[j].innerHTML = '<i class=\"far fa-play-circle\"></i>';\n          }\n        }\n\n        audios[i].play();\n        playButtons[i].innerHTML = '<i class=\"far fa-pause-circle\"></i>';\n      } else {\n        audios[i].pause();\n        playButtons[i].innerHTML = '<i class=\"far fa-play-circle\"></i>';\n      }\n    }, false);\n  };\n\n  for (var i = 0; i < playButtons.length; i++) {\n    _loop(i);\n  }\n};//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvcGxheWVyLmpzP2YxNTQiXSwibmFtZXMiOlsid2luZG93Iiwib25sb2FkIiwicGxheUJ1dHRvbnMiLCJkb2N1bWVudCIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJhdWRpb3MiLCJpIiwiYWRkRXZlbnRMaXN0ZW5lciIsInBhdXNlZCIsImoiLCJsZW5ndGgiLCJwYXVzZSIsImlubmVySFRNTCIsInBsYXkiXSwibWFwcGluZ3MiOiJBQUFBQSxNQUFNLENBQUNDLE1BQVAsR0FBZ0IsU0FBU0EsTUFBVCxHQUFpQjtBQUM3QixNQUFJQyxXQUFXLEdBQUdDLFFBQVEsQ0FBQ0Msc0JBQVQsQ0FBZ0MsVUFBaEMsQ0FBbEI7QUFDQSxNQUFJQyxNQUFNLEdBQUdGLFFBQVEsQ0FBQ0Msc0JBQVQsQ0FBZ0MsT0FBaEMsQ0FBYjs7QUFGNkIsNkJBSXBCRSxDQUpvQjtBQUt6QkosZUFBVyxDQUFDSSxDQUFELENBQVgsQ0FBZUMsZ0JBQWYsQ0FBZ0MsT0FBaEMsRUFBd0MsWUFBSTtBQUN4QyxVQUFHRixNQUFNLENBQUNDLENBQUQsQ0FBTixDQUFVRSxNQUFiLEVBQW9CO0FBQ2hCLGFBQUssSUFBSUMsQ0FBQyxHQUFHLENBQWIsRUFBZ0JBLENBQUMsR0FBR1AsV0FBVyxDQUFDUSxNQUFoQyxFQUF3Q0QsQ0FBQyxFQUF6QyxFQUE2QztBQUN6QyxjQUFHLENBQUNKLE1BQU0sQ0FBQ0ksQ0FBRCxDQUFOLENBQVVELE1BQWQsRUFBcUI7QUFDakJILGtCQUFNLENBQUNJLENBQUQsQ0FBTixDQUFVRSxLQUFWO0FBQ0FULHVCQUFXLENBQUNPLENBQUQsQ0FBWCxDQUFlRyxTQUFmLEdBQXlCLG9DQUF6QjtBQUNIO0FBQ0o7O0FBQ0RQLGNBQU0sQ0FBQ0MsQ0FBRCxDQUFOLENBQVVPLElBQVY7QUFDQVgsbUJBQVcsQ0FBQ0ksQ0FBRCxDQUFYLENBQWVNLFNBQWYsR0FBeUIscUNBQXpCO0FBQ0gsT0FURCxNQVNLO0FBQ0RQLGNBQU0sQ0FBQ0MsQ0FBRCxDQUFOLENBQVVLLEtBQVY7QUFDQVQsbUJBQVcsQ0FBQ0ksQ0FBRCxDQUFYLENBQWVNLFNBQWYsR0FBeUIsb0NBQXpCO0FBQ0g7QUFDSixLQWRELEVBY0UsS0FkRjtBQUx5Qjs7QUFJN0IsT0FBSyxJQUFJTixDQUFDLEdBQUcsQ0FBYixFQUFnQkEsQ0FBQyxHQUFHSixXQUFXLENBQUNRLE1BQWhDLEVBQXdDSixDQUFDLEVBQXpDLEVBQTZDO0FBQUEsVUFBcENBLENBQW9DO0FBZ0I1QztBQUNKLENBckJEIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3BsYXllci5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIndpbmRvdy5vbmxvYWQgPSBmdW5jdGlvbiBvbmxvYWQoKXtcbiAgICB2YXIgcGxheUJ1dHRvbnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwicGxheV9idG5cIilcbiAgICB2YXIgYXVkaW9zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcImF1ZGlvXCIpXG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IHBsYXlCdXR0b25zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIHBsYXlCdXR0b25zW2ldLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywoKT0+e1xuICAgICAgICAgICAgaWYoYXVkaW9zW2ldLnBhdXNlZCl7XG4gICAgICAgICAgICAgICAgZm9yIChsZXQgaiA9IDA7IGogPCBwbGF5QnV0dG9ucy5sZW5ndGg7IGorKykge1xuICAgICAgICAgICAgICAgICAgICBpZighYXVkaW9zW2pdLnBhdXNlZCl7XG4gICAgICAgICAgICAgICAgICAgICAgICBhdWRpb3Nbal0ucGF1c2UoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHBsYXlCdXR0b25zW2pdLmlubmVySFRNTD0nPGkgY2xhc3M9XCJmYXIgZmEtcGxheS1jaXJjbGVcIj48L2k+JztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBhdWRpb3NbaV0ucGxheSgpO1xuICAgICAgICAgICAgICAgIHBsYXlCdXR0b25zW2ldLmlubmVySFRNTD0nPGkgY2xhc3M9XCJmYXIgZmEtcGF1c2UtY2lyY2xlXCI+PC9pPic7XG4gICAgICAgICAgICB9ZWxzZXtcbiAgICAgICAgICAgICAgICBhdWRpb3NbaV0ucGF1c2UoKTtcbiAgICAgICAgICAgICAgICBwbGF5QnV0dG9uc1tpXS5pbm5lckhUTUw9JzxpIGNsYXNzPVwiZmFyIGZhLXBsYXktY2lyY2xlXCI+PC9pPic7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sZmFsc2UpXG4gICAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/player.js\n");

/***/ }),

/***/ 1:
/*!**************************************!*\
  !*** multi ./resources/js/player.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/segway/code/music_sns/resources/js/player.js */"./resources/js/player.js");


/***/ })

/******/ });