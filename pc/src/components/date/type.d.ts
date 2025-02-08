type DatePickerDate = DateSource | Partial<SimpleDateParts> | null;
type DateSource = Date | string | number;
interface SimpleDateParts {
    year: number;
    month: number;
    day: number;
    hours: number;
    minutes: number;
    seconds: number;
    milliseconds: number;
}
type DatePickerRangeObject = {
    start: Exclude<DatePickerDate, null>;
    end: Exclude<DatePickerDate, null>;
};
