import AjaxPlugin from './ajax';
import EasterConfettiPlugin from './plugin/easter-confetti/easter-confetti.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('AjaxPlugin',AjaxPlugin,'[data-ajax-plugin]');
PluginManager.register('EasterConfetti', EasterConfettiPlugin, '[data-easter-confetti]');

