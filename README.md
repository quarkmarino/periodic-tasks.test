# Repeating Time Scales
- Times
    - Starts on `start_at`
    - runs for the `times` number of times

All task dates start from `starts_at` and end before or at `ends_at`, and runs for the `times` number of times or unlimited if `times` is null

- Day
    - Repeats every `every` number of days e.g.
        - Day (1), runs every single day
        - Day (2), runs every other day
        - Day (3), runs every third day
- Week
    - Repeats every week at the selected week_days, any of (sun, mon, tue, wen, thu, fri, sat)
- Month
    - Repeats every nth day of the month, 01-31, (Jan, Feb, Mar, Apr, May, Jun, Jul, Ago, Sep, Oct, Nov, Dec)
    - Repeats every nth week day of the month cross operation between
        Nth day, (first, second, third, fourth, fifth)
        Week day, (sun, mon, tue, wen, thu, fri, sat)
- Year
    - Repeats every nth day of the year, 01-[365,366]
