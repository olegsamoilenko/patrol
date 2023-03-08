import dayjs from 'dayjs/esm/index.js';
import uk from '../locale/dayjs/uk.js';
import relativeTime from 'dayjs/esm/plugin/relativeTime/index.js';

export function date() {

    dayjs.extend(relativeTime);
    function humanizeDate(date) {
        if (!date) {
            return null;
        }
        return dayjs(date).locale('uk', uk).fromNow();
    }

    function formattedDate(date) {
        if (!date) {
            return null;
        }
        return dayjs(date).locale('uk', uk).format('DD MMMM YYYY, HH:mm');
    }

    return { humanizeDate, formattedDate };
}
