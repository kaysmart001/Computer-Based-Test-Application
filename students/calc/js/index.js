'use strict';

/* 
Design credit goes to Jaroslav Getman
https://dribbble.com/shots/2334270-004-Calculator

Hello codepen people! I think the js part of the calculator is working... maybe. It is modeled to work like the calculator on Windows. 

The parsing for the mathematical expressions is not really efficient, because it creates a brand new AST (Abstract Syntax Tree) every time an operand or number is added. I imagine there's some other extremely efficient parsing algorithm that could have been used, but this was the easiest for me to understand and make. Let me know if anything is off. Thanks!

Keybindings:
- Backspace: Clears the last digit in the current operand
- Delete: Sets the current operand to 0
- Numberpad: They keys of a standard numpad will work. 
- Parens: Shift + ( or Shift + )

Todo: 
- Make functions pure
- When a keybinding is pressed, make the keypad show the key that was pressed
- Use the total as the current operand when the current total is negated or when there is no previous operand (done?)
- Rename "state.expressions" to "state.expressionParts"
- Maybe auto add closing parens
- How should expression overflow off the left side of the screen be shown?
- Vertical align operators and operands in expression list display

Bugs:
- Fix leading zero display error when a 0 is negated and subsequently appended onto.

- I don't think pressing an operator on the default screen should result in adding the current operand to the expression. However, it technically should be showing the renderTotal screen data, which should not include the previously entered expressions, so I guess it's okay. 

- Fix issue where the current operand and current operator are added every time a new operator is selected

- Not sure what should happen after a closing paren is added right after an opening paren. At the moment it just adds the current operand. 

*/

var classes = {
	buttons: '.js-calculator-button',
	equals: '.js-calculator-equals',
	header: '.js-calculator-header',
	expressionsDisplay: '.js-calculator-expressions-display',
	expressionOverflow: '.js-calculator-expressions-overflow',
	currentOperand: '.js-current-operand'
};

// Shows the current operator
var $currentOperator = $(classes.currentOperator);
// Displays the current operand
var $currentOperand = $(classes.currentOperand);
// Display for exprawdaw
var $expressionsDisplay = $(classes.expressionsDisplay);
// The calculator header which shows the current operand and expression display
var $header = $(classes.header);
var $expressionOverflow = $(classes.expressionOverflow);

$$(classes.buttons).filter(function (button) {
	return !button.classList.contains('empty');
}).forEach(function (button) {
	button.addEventListener('click', function onButtonClick(e) {
		check(e.target.textContent.trim());
	});
});

$(classes.equals).addEventListener('click', function () {
	return renderTotal(state);
});

// Mode show total causes the total to be displayed in the current operand display
var MODE_SHOW_TOTAL = 1 << 1;
// Mode insert operand causes the current operand to be overwritten. After the first character has been written, the mode should go to mode append operand
var MODE_INSERT_OPERAND = 1 << 2;
// Mode append operand causes any operand parts to be appended to the current operand
var MODE_APPEND_OPERAND = 1 << 3;

// The maximum number of digits the current operand may be
var MAX_NUMBER_LENGTH = 16;

var RE_PAREN = /^[()]$/;
var RE_OPERAND_PART = /^[0-9.]$/;
var RE_OPERATOR = /^[/*\-+%√]$/;
var RE_DIGIT = /[0-9]+/;

var state = {
	expressions: ['5', '+', '7', '-', '45', '+', '3', '+', '177', '-'],
	currentOperand: '147',
	currentOperator: '-',
	previousOperand: '177',
	mode: MODE_SHOW_TOTAL | MODE_INSERT_OPERAND,
	openParenStack: 0
};

var lastCharacter = '-';

setTimeout(function () {
	render(state);
	console.log(getTotal(state));
});

/**
 * Displays the current total and clears previous expressions
 */
function renderTotal(state) {
	var message = 'Error';

	if (state.mode & MODE_APPEND_OPERAND) {
		// FIXME: This breaks the pure function thing I was wanting to translate into
		state.expressions.push(state.currentOperand);
		console.log('Adding current operand to expressions');
	}

	if (state.expressions.length === 2) {
		var expressionString = state.expressions.join(' ') + ' ' + state.currentOperand;
		message = new Evaluator({ data: expressionString }).eval();
	} else {
		try {
			message = getTotal(state);
		} catch (err) {
			$currentOperand.classList.add('has-error');
		}
	}

	// If you move to pure functions how will this be achieved?
	state = clearExpressions({ currentOperand: state.currentOperand });

	var _getNewFont = getNewFont(message.length);

	var fontSize = _getNewFont.fontSize;
	var fontWeight = _getNewFont.fontWeight;

	console.log(fontSize, fontWeight);
	$currentOperand.style.fontWeight = fontWeight;
	$currentOperand.style.fontSize = fontSize;
	$currentOperand.textContent = message;
}

function render(state) {
	var operand = undefined;
	var operandLength = undefined;

	if ($currentOperand.classList.contains('has-error')) {
		$currentOperand.classList.remove('has-error');
	}

	$expressionsDisplay.textContent = expressionsToDisplayString(state.expressions);

	if (state.mode & MODE_SHOW_TOTAL) {
		// If there is no previous operand, use the current operand as the total
		if (state.previousOperand === '') {
			operand = state.currentOperand;
		} else {
			try {
				operand = getTotal(state);
			} catch (err) {
				console.log(err);
				return;
			}
		}
	} else {
		operand = state.currentOperand;
	}

	$currentOperand.textContent = operand;

	var _getNewFont2 = getNewFont(operand.length);

	var fontSize = _getNewFont2.fontSize;
	var fontWeight = _getNewFont2.fontWeight;

	$currentOperand.style.fontWeight = fontWeight;
	$currentOperand.style.fontSize = fontSize;

	var expressionsWidth = $expressionsDisplay.getBoundingClientRect().width;
	var headerWidth = $header.getBoundingClientRect().width;

	if (expressionsWidth + 30 > headerWidth) {
		$expressionOverflow.classList.add('is-showing');
	} else {
		$expressionOverflow.classList.remove('is-showing');
	}
}

function getNewFont(stringLength) {
	var fontSize = undefined;
	var fontWeight = undefined;

	if (stringLength < 8) {
		fontSize = '60px';
		fontWeight = '200';
	} else if (stringLength <= MAX_NUMBER_LENGTH) {
		fontSize = '28px';
		fontWeight = '300';
	} else if (stringLength >= MAX_NUMBER_LENGTH) {
		fontSize = '24px';
		fontWeight = '300';
	}

	return { fontSize: fontSize, fontWeight: fontWeight };
}

/**
 * Prepares the expressions array for display
 * @param {Array} array
 * @return {String}
 */
function expressionsToDisplayString(arr) {
	return arr.map(function (str, index, array) {
		var s = str.trim();

		if (array[index - 1] === '(') {
			return s;
		} else if (s === ')') {
			return s;
		} else if (s[0] === '-' && isOperandPart(s[1])) {
			return ' ' + str;
		} else if (s === '√') {
			return ' yroot';
		} else {
			return ' ' + s;
		}

		return str;
	}).join('');
}

function getTotal(state) {
	var expressionString = undefined;

	if (state.expressions.length === 0) {
		return String(state.currentOperand);
	}

	if (isOperator(last(state.expressions))) {
		expressionString = state.expressions.filter(function (item, index, array) {
			return index !== array.length - 1;
		}).join(' ');
	} else {
		expressionString = state.expressions.join('');
	}

	console.log('"' + expressionString + '"');
	return String(new Evaluator({ data: expressionString }).eval());
}

function appendCurrentOperand(value) {
	var newMode = state.mode;
	var newCurrentOperand = state.currentOperand;

	// Disallow leading zeros
	if (value === '0' && state.currentOperand[0] === '0') {
		return;
	}

	// Avoid appended multiple decimals
	if (value === '.' && state.currentOperand.includes('.')) {
		return;
	}

	// 	Switch modes from showing the total to the current operand
	if (state.mode & MODE_SHOW_TOTAL) {
		newMode = MODE_INSERT_OPERAND;
		// console.log('switching to mode insert operand')
	}

	//
	if (state.mode & MODE_INSERT_OPERAND) {
		newCurrentOperand = value;
		newMode = MODE_APPEND_OPERAND;
		// console.log('Moving to mode append operand')		
	} else {
			newCurrentOperand += value;
			// console.log('In mode append operand')
		}

	return Object.assign({}, state, {
		currentOperand: newCurrentOperand.substring(0, MAX_NUMBER_LENGTH),
		mode: newMode
	});
}

function addOperator(operator) {
	var _state = state;
	var newExpressions = _state.expressions;
	var newOperator = _state.currentOperator;

	// console.log('adding operator:', state)
	// console.log('lastCharacter:', lastCharacter);

	// Don't append operators right after opening parens

	if (lastCharacter === '(') {
		console.log('paren is before this');
		return;
	}

	// Update the current operator instead of adding a new one
	if (isOperator(lastCharacter)) {
		console.log('Updating current operator');
		newOperator = operator;
		newExpressions = state.expressions.filter(function (item, index, array) {
			return index !== array.length - 1;
		}).concat([newOperator]);
	} else {
		console.log('Adding current operand and operator');
		// Handle case where the part on the left is an expression		
		if (lastCharacter === ')') {
			newExpressions = state.expressions.concat([operator]);
		} else {
			newExpressions = state.expressions.concat([state.currentOperand, operator]);
		}

		newOperator = operator;
	}

	return Object.assign({}, state, {
		currentOperator: newOperator,
		previousOperand: state.currentOperand,
		expressions: newExpressions,
		mode: MODE_INSERT_OPERAND | MODE_SHOW_TOTAL
	});
}

function clearExpressions(newState) {
	return Object.assign({}, state, {
		expressions: [],
		currentOperand: '0',
		previousOperand: '',
		currentOperator: '',
		mode: MODE_SHOW_TOTAL | MODE_INSERT_OPERAND
	}, newState);
}

function negateCurrentOperand() {
	var _state2 = state;
	var newMode = _state2.mode;
	var newCurrentOperand = _state2.currentOperand;

	if (state.mode & MODE_SHOW_TOTAL) {
		// NOTE: Maybe a try catch here? getTotal throws, but it shouldn't throw here		
		var total = getTotal(state);
		newCurrentOperand = '' + (Math.sign(total) === 0 ? '-' : '') + total;
		newMode = MODE_APPEND_OPERAND;
		// console.log('Using total for negation')
	} else {
			if (state.currentOperand[0] === '-') {
				newCurrentOperand = state.currentOperand.substring(1, state.currentOperand.length);
			} else {
				newCurrentOperand = '-' + state.currentOperand;
			}
			// console.log('Using current operand for negation')
		}

	return Object.assign({}, state, {
		mode: newMode,
		currentOperand: newCurrentOperand
	});
}

function addParen(paren) {
	var _state3 = state;
	var newMode = _state3.mode;
	var openParenStack = _state3.openParenStack;
	var newExpressions = _state3.expressions;

	if (paren === '(') {
		newExpressions = newExpressions.concat(['(']);
		openParenStack++;
	} else {
		if (openParenStack <= 0) {
			return;
		}

		newExpressions = newExpressions.concat([state.currentOperand, ')']);
		newMode = MODE_SHOW_TOTAL | MODE_INSERT_OPERAND;
		openParenStack--;
	}

	return Object.assign({}, state, {
		expressions: newExpressions,
		mode: newMode,
		openParenStack: openParenStack
	});
}

function backspace() {
	var _state4 = state;
	var currentOperand = _state4.currentOperand;

	var newMode = MODE_APPEND_OPERAND;
	var newCurrentOperand = currentOperand.substring(0, currentOperand.length - 1);

	if (newCurrentOperand === '') {
		newCurrentOperand = '0';
		newMode = MODE_INSERT_OPERAND;
	}

	return Object.assign({}, state, {
		currentOperand: newCurrentOperand,
		mode: newMode
	});
}

function clearCurrentOperand() {
	return Object.assign({}, state, {
		currentOperand: '0',
		mode: MODE_INSERT_OPERAND
	});
}

// Change each function to return state instead of modifying it
function check(value) {
	if (isOperandPart(value)) {
		state = appendCurrentOperand(value);
	} else if (isOperator(value)) {
		state = addOperator(value);
	} else if (value === '+/-') {
		state = negateCurrentOperand();
	} else if (value === 'C') {
		state = clearExpressions();
	} else if (value === 'c') {
		state = clearCurrentOperand();
	} else if (value === '\b') {
		state = backspace();
	} else if (value === '\n') {
		renderTotal(state);
	} else if (isParen(value)) {
		state = addParen(value);
	}

	lastCharacter = value;
	render(state);
}

/**
 * Returns the last item in the collection
 * @param {Collection} collection
 * @return {*}
 */
function last(collection) {
	return collection[collection.length - 1];
}

/**
 * Returns true if the value is an operand
 * @param  {String}  value
 * @return {Boolean}
 */
function isOperandPart(value) {
	return RE_OPERAND_PART.test(value);
}

/**
 * Returns true if the value is an operator
 * @param  {String}  value
 * @return {Boolean}
 */
function isOperator(value) {
	return RE_OPERATOR.test(value);
}

/**
 * Returns true if the value is an opening or closing paren.
 * @param {String} value
 * @return {Boolean}
 */
function isParen(value) {
	return RE_PAREN.test(value);
}

/**
 * A very naive way of testing if a string is a number. 
 * (Don't use this in production)
 * @param {String} value
 * @return {Boolean}
 */
function isNumber(value) {
	return RE_DIGIT.test(value);
}

// Handle keybindings
window.addEventListener('keypress', function onWindowKeypress(e) {
	var char = String.fromCharCode(e.keyCode);
	// console.log(String.fromCharCode(e.keyCode), e);

	if (char === '-' && e.shiftKey) {
		e.preventDefault();
		check('+/-');
	} else if (char === '\b') {
		console.log('backspace?');
	} else {
		check(char);
	}
});

window.addEventListener('keydown', function onWindowKeydown(e) {
	var KEY_BACKSPACE = 8;
	var KEY_DELETE = 46;
	var KEY_ENTER = 13;
	var KEY_ESCAPE = 27;

	switch (e.keyCode) {
		case KEY_BACKSPACE:
			check('\b');
			return e.preventDefault();

		case KEY_DELETE:
			check('c');
			return e.preventDefault();

		case KEY_ENTER:
			check('\n');
			return e.preventDefault();

		case KEY_ESCAPE:
			check('C');
			return e.preventDefault();
	}
});

// module, exports are mocks of commonjs because I wrote and tested this in nodejs

var _ref = function (module, exports) {
	'use strict';

	/**
  * LEXER AND PARSER for mathematical expressions. The lexer is hand written with influences from Stylus's lexer, but the parser was mostly taken from elsewhere, which is listed in the description for the Parser constructor.
  */

	var TYPE_NUMBER_LITERAL = 'NUMBER_LITERAL';

	/**
  * A lexer, inspired by Stylus's lexer.
  * @constructor
  * @param {String} options.data
  */
	var Lexer = exports.Lexer = function Lexer(_ref2) {
		var data = _ref2.data;

		this.input = String(data);
		this.position = 0;
		this.char = this.input[this.position];
		this.tokens = [];
		this.stash = [];
	};

	var operators = ['/', '*', '**', '-', '+', '√', '%'];

	var longestOperatorLength = operators.reduce(function (length, item) {
		if (item.length > length) {
			return item.length;
		}

		return length;
	}, 0);

	var RE_DIGIT = /[0-9]/;
	var RE_PAREN = /[()]/;
	var RE_WHITESPACE = /\s/;
	var RE_OPERATOR_START = new RegExp(Array.from(new Set(operators.map(function (str) {
		return str[0];
	}))).map(function (str) {
		return escapeRegExp(str);
	}).join('|'));

	// All the operators, sorted by longest string length
	var RE_OPERATOR_WHOLE = new RegExp(Array.from(operators).sort(function (a, b) {
		return b.length - a.length;
	}).map(function (str) {
		return escapeRegExp(str);
	}).join('|'));

	/**
  * Courtesy of
  * http://stackoverflow.com/questions/3446170/escape-string-for-use-in-javascript-regex
  * @param  {String} str
  * @return {String}
  */
	function escapeRegExp(str) {
		return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
	}

	function isDigit(char) {
		return RE_DIGIT.test(char);
	}

	function isOperatorStart(char) {
		return RE_OPERATOR_START.test(char);
	}

	function isParen(char) {
		return RE_PAREN.test(char);
	}

	function isWhitespace(char) {
		return RE_WHITESPACE.test(char);
	}

	Object.assign(Lexer.prototype, {
		/**
   * Moves the lexer's current character to the next character in the input.
   * Returns '\x00' if the position is passed the input
   * @private
   * @return {String}
   */

		advance: function advance() {
			return this.char = this.input[++this.position] || '\x04';
		},

		/**
   * Looks ahead into the token stream by the index passed. The tokens
   * are cached for performance.
   * @public
   * @param  {Number} index
   * @return {void}
   */
		lookahead: function lookahead(index) {
			var times = index - this.stash.length;

			if (this.position > this.input.length) {
				return '\x04';
			}

			while (times-- > 0) {
				var token = this.lex();

				while (token === '\x00') {
					token = this.lex();
				}

				this.stash.push(token);
			}

			return this.stash[index - 1];
		},

		/**
   * Looks into the token stream one token ahead
   * @public
   * @return {String}
   */
		peek: function peek() {
			return this.lookahead(1);
		},
		getNextChar: function getNextChar() {
			return this.input[this.position + 1];
		},
		getPreviousChar: function getPreviousChar() {
			return this.input[this.position - 1];
		},

		/**
   * Returns the next token in the token stream
   * @public
   * @return {String}
   */
		next: function next() {
			var token = undefined;

			if (this.position > this.input.length) {
				return '\x04';
			}

			while (true) {
				if (this.stash.length) {
					return this.stash.shift();
				}

				token = this.lex();

				if (token !== '\x00') {
					return token;
				}
			}

			throw new Error('wtf this should be unreachable: lexer.next');
		},

		/**
   * Moves the lexer's position the specified length
   * @private
   * @param  {Number} times
   * @return {void}
   */
		skip: function skip(length) {
			this.position += length;
			this.char = this.input[this.position];
		},

		/**
   * Stores the most recently found literal
   * @private
   * @param {void} literal
   */
		setLiteral: function setLiteral(literal) {
			this.currentLiteral = literal;
		},

		/**
   * Returns the most recently lexed literal. Always returns the value as a string.
   * @public
   * @return {String}
   */
		getLiteral: function getLiteral() {
			return this.currentLiteral;
		},

		/**
   * Returns the next token from the lexer's input.
   * Returns null when there are no more tokens to be consumed
   * @private
   * @return {String|null}
   */
		lex: function lex() {
			if (this.position >= this.input.length) {
				return '\x04';
			}

			if (isWhitespace(this.char)) {
				this.advance();
				return '\x00';
			}

			var token = this.getParenToken() || this.getNumberToken() || this.getOperatorToken();

			if (token === null || token === undefined) {
				throw new Error('Unrecognized token "' + this.char + '" at position ' + this.position);
			}

			return token;
		},

		/**
   * Returns a paren punctuation character
   * @return {String|null}
   */
		getParenToken: function getParenToken() {
			var char = this.char;

			if (isParen(this.char)) {
				this.advance();
				return char;
			}

			return null;
		},

		/**
   * Returns constant TYPE_NUMBER_LITERAL if number is found
   * @return {String|null}
   */
		getNumberToken: function getNumberToken() {
			var numberLiteral = this.char;

			if (isDigit(this.char)) {
				while (isDigit(this.advance())) {
					numberLiteral += this.char;
				}

				if (this.char === '.') {
					do {
						numberLiteral += this.char;
					} while (isDigit(this.advance()));
				}
			} else {
				return null;
			}

			this.setLiteral(numberLiteral);

			if (numberLiteral.length) {
				return TYPE_NUMBER_LITERAL;
			} else {
				return null;
			}
		},

		/**
   * Returns operator punctuation character
   * @return {String|null}
   */
		getOperatorToken: function getOperatorToken() {
			var char = this.char;

			if (isOperatorStart(this.char)) {
				var substr = this.input.substring(this.position, this.position + longestOperatorLength);
				var match = substr.match(RE_OPERATOR_WHOLE);

				if (!match) {
					throw new Error('wtf dooood there was not a opeator token found...');
				}

				var length = match[0].length;

				while (length-- > 0) {
					this.advance();
				}

				return match[0];
			}

			return null;
		}
	});

	/**
  * Recursive descent parser modified from
  * https://github.com/mattgoldspink/tapdigit/blob/master/TapDigit.js#L448
 
 	Copyright (C) 2011 Ariya Hidayat <ariya.hidayat@gmail.com>
 	Copyright (C) 2010 Ariya Hidayat <ariya.hidayat@gmail.com>
 	All rights reserved.
 
 	Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 
 	1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 
 	2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
 
 	3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
 
 	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  *
  * This version adds support for exponents and nth root via the '**' and '√' operators
  *
  * Note: This throw errors when passed a lexer that is parsing an empty string
  * @constructor
  * @param {Lexer} options.lexer
  */
	var Parser = exports.Parser = function Parser(_ref3) {
		var lexer = _ref3.lexer;

		this.lexer = lexer;
		this.position = 0;
	};

	Object.assign(Parser.prototype, {
		parsePrimary: function parsePrimary() {
			var token = this.lexer.peek();
			var expression = undefined;

			if (token === '\x00') {
				console.log('WTF NULL STRING TOKEN', token);
				throw new Error('Unexpected end of expression');
			}

			if (token === '(') {
				token = this.lexer.next();
				expression = this.parseExpression();
				token = this.lexer.next();

				if (token !== ')') {
					throw new SyntaxError('Expected ")", got ' + token);
				}

				return {
					type: 'Expression',
					expression: expression
				};
			}

			if (token === TYPE_NUMBER_LITERAL) {
				token = this.lexer.next();
				return {
					type: 'NumberLiteral',
					value: this.lexer.getLiteral()
				};
			}

			throw new SyntaxError("expected a number, a variable, or parentheses");
		},
		parseUnary: function parseUnary() {
			var token = this.lexer.peek();

			if (token === '-' || token === '+') {
				token = this.lexer.next();
				return {
					type: 'UnaryExpression',
					operator: token,
					expression: this.parseUnary()
				};
			}

			return this.parsePrimary();
		},

		// I'm not sure what these pow and nth square root operators are classified as
		parsePowAndSquare: function parsePowAndSquare() {
			var expression = this.parseUnary();
			var token = this.lexer.peek();

			while (token === '**' || token == '√') {
				token = this.lexer.next();
				expression = {
					type: 'BinaryExpression',
					operator: token,
					left: expression,
					right: this.parseUnary()
				};
				token = this.lexer.peek();
			}

			return expression;
		},
		parseMultiplicative: function parseMultiplicative() {
			var expression = this.parsePowAndSquare();
			var token = this.lexer.peek();

			while (token === '*' || token == '/' || token === '%') {
				token = this.lexer.next();
				expression = {
					type: 'BinaryExpression',
					operator: token,
					left: expression,
					right: this.parsePowAndSquare()
				};
				token = this.lexer.peek();
			}

			return expression;
		},
		parseAdditive: function parseAdditive() {
			var expression = this.parseMultiplicative();
			var token = this.lexer.peek();

			while (token === '+' || token === '-') {
				var operator = token;
				token = this.lexer.next();
				expression = {
					type: 'BinaryExpression',
					operator: token,
					left: expression,
					right: this.parseMultiplicative()
				};
				token = this.lexer.peek();
			}

			return expression;
		},
		parseExpression: function parseExpression() {
			return this.parseAdditive();
		},

		parse: function parse() {
			var lexer = this.lexer;

			var expression = this.parseExpression();

			return {
				type: 'ExpressionStatement',
				expression: expression
			};
		}
	});

	var operations = {
		'+': function _(a, b) {
			return a + b;
		},
		'-': function _(a, b) {
			return a - b;
		},
		'*': function _(a, b) {
			return a * b;
		},
		'/': function _(a, b) {
			return a / b;
		},
		'%': function _(a, b) {
			return a % b;
		},
		'**': function _(a, b) {
			return Math.pow(a, b);
		},
		// NOTE: Apparently this is a naive implementation of nth root
		// http://stackoverflow.com/questions/7308627/javascript-calculate-the-nth-root-of-a-number
		'√': function _(a, b) {
			return Math.pow(a, 1 / b);
		}
	};

	/**
  * Evaluates the AST produced from the parser and returns its result
  * @return {Number}
  */
	var evaluate = exports.evaluate = function evaluate(node) {
		var e = undefined;
		var a = undefined;
		var b = undefined;

		switch (node.type) {
			case 'ExpressionStatement':
				return evaluate(node.expression);
			case 'Expression':
				return evaluate(node.expression);
			case 'NumberLiteral':
				return parseFloat(node.value);
			case 'UnaryExpression':
				a = evaluate(node.expression);

				switch (node.operator) {
					case '+':
						return +a;
					case '-':
						return -a;
					default:
						throw new Error('Parsing error: Unrecognized unary operator "' + node.operator + '"');
				}
			case 'BinaryExpression':
				var left = node.left;
				var right = node.right;
				var operator = node.operator;

				var operation = operations[operator];

				if (operation === undefined) {
					throw new Error('Unsupported operand');
				}

				return operation(evaluate(left), evaluate(right));
			default:
				throw new Error('Parsing error: Unrecognized node type "' + node.type + '"');
		}
	};

	/**
  * Evaluates the expression passed and returns its result.
  * Note: Empty strings will cause the parser to throw an error.
  * Note: This throw errors when passed a lexer that is parsing an empty string
  * @constructor
  * @return {Number}
  */
	var Evaluator = exports.Evaluator = function Evaluator(_ref4) {
		var data = _ref4.data;

		this.input = String(data);
	};

	Object.assign(Evaluator.prototype, {
		/**
   * Evaluates the input passed through the constructor
   * @public
   * @return {Number}
   */
		eval: function _eval() {
			var parser = new Parser({
				lexer: new Lexer({ data: this.input })
			});

			var ast = parser.parse();
			// console.log(require('util').inspect(ast, true, 20))
			return evaluate(ast);
		}
	});
	return { Lexer: Lexer, Evaluator: Evaluator, Parser: Parser };
}({ exports: {} }, {});

var Lexer = _ref.Lexer;
var Parser = _ref.Parser;
var Evaluator = _ref.Evaluator;

// Debug function for flags

function getFlags(flags) {
	var arr = [];

	if (flags & MODE_SHOW_TOTAL) {
		arr.push('MODE_SHOW_TOTAL');
	}
	if (flags & MODE_INSERT_OPERAND) {
		arr.push('MODE_INSERT_OPERAND');
	}

	if (flags & MODE_APPEND_OPERAND) {
		arr.push('MODE_APPEND_OPERAND');
	}

	return arr.join('|');
}