
Q=@
BUILDER=pdflatex
BUILDER_OPTS=-interaction nonstopmode
CLEAN_FILE_PATTERNS=*.aux *.toc *.log
BUILD_DIR=build/

.PHONY: build_user_manual build_admin_manual build_dev_manual clean

all: build clean

%.pdf: %.tex
	${BUILDER} ${BUILDER_OPTS} $<
	${BUILDER} ${BUILDER_OPTS} $<

build: user_manual.pdf admin_manual.pdf developer_manual.pdf
	${Q}mkdir -p ${BUILD_DIR}
	${Q}mv -f *.pdf ${BUILD_DIR}

clean:
	${Q}rm -f ${CLEAN_FILE_PATTERNS}
