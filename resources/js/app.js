import "./bootstrap";
import Alpine from "alpinejs";
import focus from "@alpinejs/focus";

import { polling } from "./alpine/utilities/polling";
import directives from "./alpine/directives";

window.polling = polling;

Alpine.plugin(focus);
directives(Alpine);

Alpine.start();
