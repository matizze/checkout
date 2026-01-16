import { polling } from "../utilities/polling";

export default function () {
    return polling("/api/notifications/flasher", 3000);
}
