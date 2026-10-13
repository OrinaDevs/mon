<?php
/**
 * Returns Bootstrap pagination HTML, or '' when there is only one page.
 * $urlFor(int $page): string builds the address for a given page.
 */
function pagination_links(int $page, int $pages, callable $urlFor): string
{
    if ($pages <= 1) {
        return '';
    }

    // First page, last page, and two either side of the current one.
    $show = [1, $pages];
    for ($i = $page - 2; $i <= $page + 2; $i++) {
        if ($i >= 1 && $i <= $pages) {
            $show[] = $i;
        }
    }
    $show = array_values(array_unique($show));
    sort($show);

    $item = function (string $label, ?int $target, bool $active = false) use ($urlFor): string {
        if ($target === null) {
            return '<li class="page-item disabled"><span class="page-link">' . $label . '</span></li>';
        }
        return '<li class="page-item' . ($active ? ' active' : '') . '"'
             . ($active ? ' aria-current="page"' : '') . '>'
             . '<a class="page-link" href="' . e($urlFor($target)) . '">' . $label . '</a></li>';
    };

    $html = '<nav aria-label="Product pages"><ul class="pagination justify-content-center flex-wrap mt-5">';
    $html .= $item('Previous', $page > 1 ? $page - 1 : null);

    $last = 0;
    foreach ($show as $n) {
        if ($n - $last > 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
        }
        $html .= $item((string) $n, $n, $n === $page);
        $last = $n;
    }

    $html .= $item('Next', $page < $pages ? $page + 1 : null);
    $html .= '</ul></nav>';

    return $html;
}