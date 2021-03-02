import pop from 'compop';
import './polyfills';

// Components
import Header from './components/header';
import Input from './components/input';
import Main from './components/main';
import Modal from './components/modal';

const SITE_HANDLE = 'pb';

// Define map of component handles to component classes
/* eslint-disable quote-props */
const classMap = {
    'header': Header,
    'input': Input,
    'main': Main,
    'modal': Modal,
};
/* eslint-enable quote-props */

// Define all actions/commands that components pub/sub
const actions = {
    loadModal: 'LOAD_MODAL',
    openModal: 'OPEN_MODAL',
    closeModal: 'CLOSE_MODAL',
    lockScroll: 'LOCK_SCROLL',
    unlockScroll: 'UNLOCK_SCROLL',
    setInputValue: 'SET_INPUT_VALUE',
    setInputError: 'SET_INPUT_ERROR',
};

// Event handler functions
function handleDOMConentLoaded() {
    const scaffold = window[SITE_HANDLE];

    // Functionality for after components initialize
    function cb() {
        // ...
    }

    // Call component constructors
    pop({ scaffold, classMap, actions, cb });
}

// Add event listeners
document.addEventListener('DOMContentLoaded', handleDOMConentLoaded);
