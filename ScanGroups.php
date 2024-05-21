<?php
$servername = "123.31.24.10";
$username = "root";
$password = "vuduchong123";
$dbname = "monitaz_scan_paper";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, content_file, status FROM scan_groups";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $keywords = json_decode($row["content_file"]);
        foreach ($keywords as $keyword) {
            $token = getToken();
            run1($token, $keyword, $row["name"]);
        }
    }
} else {
    echo "0 results";
}
$conn->close();

$key ='ngân hàng Viettinbank';
// $token ='EAAAAUaZA8jlABOy2BpTsVGfs0bWnKvcByxlkvDXbyHHpBuVOZBcgI7XfniuNqYM69Nn5YcEdJ2iwqispGfJ3c25cwZA28lTsdGCMkyJWQ5XZC94Yi60IgHsw3nGCIelSyHNhtr06ZANjrhnTNV0usvLxr7SEOZC9BAslKKT1nCljBCtkGY2mUMJUQqulmAKbsUJmHudMv35AZDZD';
$token ='EAAAAUaZA8jlABOZBXBvRTUoDuVGA9Yye2xJwesXenjpFqwmGgQDFdRTZB1UO1DHebzngyX8vLiHZAyQZBwrgdR5sVI6tZBlZB5pDcljfubPB5fUlFQlqZAvS8D0ZCgYByddXrbRQ6rhhgnvZCFAXbttx9VHQGuNaES3CkARwhvgOzgxoaHTOw4vu6egUB9aWHkcZCVTTITZAsOVOXQZDZD';
$file="tep.txt";
// $token="EAAAAUaZA8jlABOwpx41dSqXEkKl4NGexMEdUuyGPdErLEDLNRZCZB7POMOZBCPllp9FRiIfsfTUySIn8d3Dvjs5wCJzPts2YihYIc5ekzYAjDGHjNsSPbyvfHwzZCumpbP2BsTje4Gc5tccxZBOnzwUlHUrgbrE8DoA6ZCx7PbiXht97EHBbZAM9WIqt2ptzoYZCH0CkuJkFyFQZDZD";
// Set the GraphQL request payload and headers for Request 1

function getToken()
{
    return "EAAAAUaZA8jlABOZBXBvRTUoDuVGA9Yye2xJwesXenjpFqwmGgQDFdRTZB1UO1DHebzngyX8vLiHZAyQZBwrgdR5sVI6tZBlZB5pDcljfubPB5fUlFQlqZAvS8D0ZCgYByddXrbRQ6rhhgnvZCFAXbttx9VHQGuNaES3CkARwhvgOzgxoaHTOw4vu6egUB9aWHkcZCVTTITZAsOVOXQZDZD";
}

function run1($token, $key, $nameBrand)
{
// $token ='EAAAAUaZA8jlABOy2BpTsVGfs0bWnKvcByxlkvDXbyHHpBuVOZBcgI7XfniuNqYM69Nn5YcEdJ2iwqispGfJ3c25cwZA28lTsdGCMkyJWQ5XZC94Yi60IgHsw3nGCIelSyHNhtr06ZANjrhnTNV0usvLxr7SEOZC9BAslKKT1nCljBCtkGY2mUMJUQqulmAKbsUJmHudMv35AZDZD';
    $file="tep.txt";
// $token="EAAAAUaZA8jlABOwpx41dSqXEkKl4NGexMEdUuyGPdErLEDLNRZCZB7POMOZBCPllp9FRiIfsfTUySIn8d3Dvjs5wCJzPts2YihYIc5ekzYAjDGHjNsSPbyvfHwzZCumpbP2BsTje4Gc5tccxZBOnzwUlHUrgbrE8DoA6ZCx7PbiXht97EHBbZAM9WIqt2ptzoYZCH0CkuJkFyFQZDZD";
// Set the GraphQL request payload and headers for Request 1
    $request1Payload = 'method=post&pretty=false&format=json&server_timestamps=true&locale=vi_VN&fb_api_req_friendly_name=SearchResultsGraphQL-main_query&fb_api_caller_class=graphservice&client_doc_id=39590791072458059906236593141&variables={"bsid":"689ab6e2-bfa7-4de1-a80b-e7a44d3194b6","entered_query_text":"","enable_bloks":true,"bloks_version":"52d2d8a68e9526056aed3023cd1a2dff2897d7f442b0158d893f8f783efd6755","search_query_arguments":{"family_device_id":"7fbb1ac8-f675-49d8-8b84-cf503c3c240f"},"default_image_scale":3,"image_low_width":407,"image_medium_width":610,"product_item_image_size":456,"image_high_width":1220,"nt_context":{"using_white_navbar":true,"pixel_ratio":3,"is_push_on":true,"styles_id":"42aba7d04bcf2e20a7c200398701a5b3","bloks_version":"52d2d8a68e9526056aed3023cd1a2dff2897d7f442b0158d893f8f783efd6755"},"scale":"3","callsite":"android:group_search","enable_at_stream":true,"tsid":"6e32b3b5-5e2c-4ef6-af96-a7047d680139","first_unit_only":true,"supported_experiences":["ADS_PARALLEL_FETCH","FAST_FILTERS","FILTERS","FILTERS_AS_SEE_MORE","INSTANT_FILTERS","MARKETPLACE_ON_GLOBAL","MIXED_MEDIA","NATIVE_TEMPLATES","NT_ENABLED_FOR_TAB","NT_SPLIT_VIEWS","PEOPLE_RADIUS_FILTER","PHOTO_STREAM_VIEWER","SEARCH_INTERCEPT","SEARCH_SNIPPETS_ICONS_ENABLED","USAGE_COLOR_SERP","commerce_groups_search","keyword_only"],"disable_story_menu_actions":false,"image_large_aspect_height":638,"bqf":"keywords_groups('.$key.')","viewer_coordinates":{"latitude":10.6575108,"timestamp":1693371248,"longitude":107.062299,"accuracy":100},"query_source":"unknown","ui_theme_name":"APOLLO_FULL_BLEED","request_index":0,"profile_image_size":282,"network_start_time":"1693371298993","image_large_aspect_width":1220,"inline_comments_location":"search"}&fb_api_analytics_tags=["main_query","GraphServices"]&client_trace_id=6e9bbd2b-15ec-4b93-886a-c8996d930a15';
    $request1Headers = array(
        "Host: graph.facebook.com",
        "Authorization: OAuth ".$token,
        "User-Agent: [FBAN/FB4A;FBAV/427.0.0.31.63;FBBV/502904498;FBDM/{density=3.0,width=1220,height=2576};FBLC/vi_VN;FBRV/506275063;FBCR/Mobifone;FBMF/Xiaomi;FBBD/Xiaomi;FBPN/com.facebook.katana;FBDV/22071212AG;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]",
        // Include other headers here
    );


// Define the URLs for the GraphQL API
    $apiUrl = "https://graph.facebook.com/graphql";

// Initialize cURL session for Request 1
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $apiUrl);
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $request1Payload);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $request1Headers);
    $response1 = curl_exec($ch1);

// Initialize cURL session for Request 2

    curl_close($ch1);


// Print responses
    $text  = explode('recent_search_entity_value":{"__typename":"Group","strong_id__":',$response1);
//echo $text[10];

    $keyToRemove = array_search($text[0], $text);
    if ($keyToRemove !== false) {
        array_splice($text, $keyToRemove, 1);
    }
    $list=[];
    foreach ($text as $row) {
        $row = explode("profile_picture", $row)[0];
        $id = explode('"', explode('"id":"', $row)[1])[0];
        if (isset($list[$id])) {
            continue;
        }
        $name = json_decode('"' . explode('"', explode('"name":"', $row)[1])[0] . '"');
        $nameLower = mb_strtolower($name);
        $keywordLower = mb_strtolower($key);
        if (!str_contains($nameLower, $keywordLower)) continue;
        $list[$id]['id_fb_page'] = $id;
        $list[$id]['name'] = $name;
        $list[$id]['name_brand'] = $nameBrand;
        $list[$id]['keyword'] = $key;
    }

    $next = explode('}',explode('has_next_page":',$response1)[1])[0];
    if($next == "true"){
        $curr = explode('"',explode('"start_cursor":null,"end_cursor":"',$response1)[1])[0];
        run2($token, $key, $curr, $list, $nameBrand);
    }
    foreach($list as $index => $value){
        $nd = $value["id_fb_page"] ."|".$value["name"];
        luu($nd, $file);
    }
}

function luu($nd, $file){
    $fh = fopen($file, 'a');
    fwrite($fh, $nd."\n");
    fclose($fh);
}

function run2($token, $key, $curr, $list, $nameBrand){
// Set the GraphQL request payload and headers for Request 1
    $request1Payload = 'method=post&pretty=false&format=json&server_timestamps=true&locale=vi_VN&fb_api_req_friendly_name=SearchResultsGraphQL-pagination_query&fb_api_caller_class=graphservice&client_doc_id=39590791072458059906236593141&variables={"enable_bloks":true,"bloks_version":"52d2d8a68e9526056aed3023cd1a2dff2897d7f442b0158d893f8f783efd6755","search_query_arguments":{"family_device_id":"7fbb1ac8-f675-49d8-8b84-cf503c3c240f"},"default_image_scale":3,"image_low_width":407,"entered_query_text":"","viewer_coordinates":{"latitude":10.6575108,"timestamp":1693371248,"longitude":107.062299,"accuracy":100},"filters_enabled":false,"disable_story_menu_actions":false,"image_large_aspect_height":638,"enable_at_stream":true,"callsite":"android:group_search","image_medium_width":610,"profile_image_size":282,"image_large_aspect_width":1220,"inline_comments_location":"search","nt_context":{"using_white_navbar":true,"pixel_ratio":3,"is_push_on":true,"styles_id":"42aba7d04bcf2e20a7c200398701a5b3","bloks_version":"52d2d8a68e9526056aed3023cd1a2dff2897d7f442b0158d893f8f783efd6755"},"image_high_width":1220,"scale":"3","bsid":"689ab6e2-bfa7-4de1-a80b-e7a44d3194b6","end_cursor":"'.$curr.'","bqf":"keywords_groups('.$key.')","supported_experiences":["FAST_FILTERS","FILTERS","FILTERS_AS_SEE_MORE","INSTANT_FILTERS","MARKETPLACE_ON_GLOBAL","MIXED_MEDIA","NATIVE_TEMPLATES","NT_ENABLED_FOR_TAB","NT_SPLIT_VIEWS","PEOPLE_RADIUS_FILTER","PHOTO_STREAM_VIEWER","SEARCH_INTERCEPT","SEARCH_SNIPPETS_ICONS_ENABLED","USAGE_COLOR_SERP","commerce_groups_search","keyword_only"],"request_index":0,"tsid":"6e32b3b5-5e2c-4ef6-af96-a7047d680139","product_item_image_size":456,"query_source":"unknown","ui_theme_name":"APOLLO_FULL_BLEED"}&fb_api_analytics_tags=["pagination_query","GraphServices"]&client_trace_id=64868e44-3739-4a49-99a0-58ee4197ae01';
    $request1Headers = array(
        "Host: graph.facebook.com",
        "Authorization: OAuth ".$token,
        "Content-Type: application/x-www-form-urlencoded",
        "User-Agent: [FBAN/FB4A;FBAV/427.0.0.31.63;FBBV/502904498;FBDM/{density=3.0,width=1220,height=2576};FBLC/vi_VN;FBRV/506275063;FBCR/Mobifone;FBMF/Xiaomi;FBBD/Xiaomi;FBPN/com.facebook.katana;FBDV/22071212AG;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]",
        // Include other headers here
    );


// Define the URLs for the GraphQL API
    $apiUrl = "https://graph.facebook.com/graphql";

// Initialize cURL session for Request 1
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $apiUrl);
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $request1Payload);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $request1Headers);
    $response1 = curl_exec($ch1);

// Initialize cURL session for Request 2

    curl_close($ch1);


    $text = explode('recent_search_entity_value":{"__typename":"Group","strong_id__":', $response1);

    $keyToRemove = array_search($text[0], $text);
    if ($keyToRemove !== false) {
        array_splice($text, $keyToRemove, 1);
    }
    foreach ($text as $row) {
        $row = explode("profile_picture", $row)[0];
        $id = explode('"', explode('"id":"', $row)[1])[0];
        if (isset($list[$id])) {
            continue;
        }
        $name = json_decode('"' . explode('"', explode('"name":"', $row)[1])[0] . '"');
        $nameLower = mb_strtolower($name);
        $keywordLower = mb_strtolower($key);
        if (!str_contains($nameLower, $keywordLower)) continue;
        $list[$id]['id_fb_page'] = $id;
        $list[$id]['name'] = $name;
        $list[$id]['name_brand'] = $nameBrand;
        $list[$id]['keyword'] = $key;
    }

    $next = explode('}', explode('has_next_page":', $response1)[1])[0];
    if ($next == "true") {
        $curr = explode('"', explode('"start_cursor":null,"end_cursor":"', $response1)[1])[0];
        sleep(1);
        $list_new = run2($token, $key, $curr, $list, $nameBrand);
    }

    $result = array_values($list);
    $this->model->update(['status' => 2]);
    $list = [];
    $dataCollect = array_chunk($result, 500);
    foreach ($dataCollect as $chunk) {
        \DB::connection('mysql24')->table('fb_pages')->insertOrIgnore($chunk->toArray());
    }
    // foreach ($this->list as $index => $row) {
    //     $nd = $key . "|" . $index . "|" . $row;
    //     $this->luu($nd, $file);
    // }
}


