#!/bin/bash

# --- configuration
ENDPOINT="https://api.ovh.com/1.0"

# --- script below

if [[ "${1}" == "-h" ]]; then
	echo "Usage : ./api-test.sh <AK> <AS> <CK> <METHOD> <QUERY> [<BODY>]"
else

# ---------- parameters

AK=${1}
AS=${2}
CK=${3}
METHOD=${4}
QUERY=$(echo ${ENDPOINT}${5})
BODY=${6}
OVH_TSTAMP=$(curl -s ${ENDPOINT}/auth/time) # retrieve time using ovh api
LOC_TSTAMP=$(date +%s)
DLT_TSTAMP=$(expr ${OVH_TSTAMP} - ${LOC_TSTAMP})
NOW_TSTAMP=$(expr ${LOC_TSTAMP} + ${DLT_TSTAMP})
HASH_BASE=$(echo ${AS}"+"${CK}"+"${METHOD}"+"${QUERY}"+"${BODY}"+"${NOW_TSTAMP})
HASH=$(php sha1.php ${HASH_BASE})
SIGN=$(echo '$1$'${HASH})

# ---------- cURL related parameters

X_OVH_APPL_HEADER=$(echo "X-Ovh-Application:${AK}")
X_OVH_TSMP_HEADER=$(echo "X-Ovh-Timestamp:${NOW_TSTAMP}")
X_OVH_SIGN_HEADER=$(echo "X-Ovh-Signature:${SIGN}")
X_OVH_CONS_HEADER=$(echo "X-Ovh-Consumer:${CK}")


# ---------- SCRIPT ZONE -----------------------------------------

echo -e "\n----------------- AUTH PARAMETERS --------------------\n"
echo -e "Application key    : ${AK}"
echo -e "Application secret : ${AS}"
echo -e "Consumer key       : ${CK}"
echo -e "\n----------------- HTTP PARAMETERS --------------------\n"
echo -e "Method     : ${METHOD}"
echo -e "Query      : ${QUERY}"
echo -e "Body       : ${BODY}"
echo -e "Ovh tstamp : ${OVH_TSTAMP}"
echo -e "Loc tstamp : ${LOC_TSTAMP}"
echo -e "Del tstamp : ${DLT_TSTAMP}"
echo -e "Now tstamp : ${NOW_TSTAMP}"
echo -e "\n--------------- COMPUTED PARAMETERS ------------------\n"
echo -e "Hash base : ${HASH_BASE}"
echo -e "Hash      : ${HASH}"
echo -e "Signature : ${SIGN}"
echo -e "\n----------------- CURL PARAMETERS --------------------\n"
echo -e "Headers :"
echo -e "	${X_OVH_APPL_HEADER}"
echo -e "	${X_OVH_TSMP_HEADER}"
echo -e "	${X_OVH_SIGN_HEADER}"
echo -e "	${X_OVH_CONS_HEADER}\n"
echo -e "Query :"
echo -e "	${QUERY}"
echo -e "\n------------------ RUNNING  CURL ---------------------\n"
echo -e "curl -s -H ${X_OVH_APPL_HEADER} -H ${X_OVH_TSMP_HEADER} -H ${X_OVH_SIGN_HEADER} -H ${X_OVH_CONS_HEADER} ${QUERY}"
echo -e "\n------------------ CURL RESPONSE ---------------------\n"
curl -s -H "${X_OVH_APPL_HEADER}" -H "${X_OVH_TSMP_HEADER}" -H "${X_OVH_SIGN_HEADER}" -H "${X_OVH_CONS_HEADER}" "${QUERY}"
echo -e "\n\n-------------------- ALL  DONE -----------------------\n"
fi



