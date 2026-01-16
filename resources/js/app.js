import "./bootstrap";
import Alpine from "alpinejs";
import focus from "@alpinejs/focus";
import mask from "@alpinejs/mask";
import collapse from "@alpinejs/collapse";

import { polling } from "./alpine/utilities/polling";
import directives from "./alpine/directives";

window.polling = polling;

Alpine.plugin(focus);
Alpine.plugin(mask);
Alpine.plugin(collapse);
directives(Alpine);

Alpine.start();
